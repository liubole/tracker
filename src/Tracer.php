<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 20:41
 */
namespace Tricolor\Tracker;
use Tricolor\Tracker\Common\Server;
use Tricolor\Tracker\Config\TraceEnv;
use Tricolor\Tracker\Core\Context;
use Tricolor\Tracker\Common\StrUtils;
use Tricolor\Tracker\Common\Time;
use Tricolor\Tracker\Common\UID;
use Tricolor\Tracker\Core\Collector;

class Tracer
{
    /**
     * Records
     * @var array
     */
    private $records;

    /**
     * Records (ignore log filter)
     * @var array
     */
    private $force_records;

    /**
     * Record filters array
     * @var array Tricolor\Tracker\Filter\Base
     */
    private static $record_filters;

    /**
     * Record results
     * @var array
     */
    private $record_results;

    /**
     * Context key: TraceId
     */
    const TraceId = 'TraceId';

    /**
     * Context key: RpcId
     */
    const RpcId = 'RpcId';
    /**
     * Names of Switch
     */
    const TraceSwitch = 'TraceSwitch';
    const LogSwitch = 'LogSwitch';
    const CollectSwitch = 'CollectSwitch';

    public function __construct()
    {
        $this->records = array();
        $this->force_records = array();
        $this->record_results = array();
        $this->builtInLog();
    }

    /**
     * Initialization the context
     * @return array
     */
    public static function init()
    {
        if (isset(TraceEnv::$TraceSwitch) && (TraceEnv::$TraceSwitch == TraceEnv::OFF)) {
            return array();
        }
        Context::set(array(
            self::TraceId => UID::create(),
            self::RpcId => StrUtils::rpcInit(Context::get(self::RpcId)),
        ));
        return Context::toArray();
    }

    /**
     * Set the method to pass the context
     * @param $carrier \Tricolor\Tracker\Carrier\Base
     * @return boolean
     */
    public static function inject($carrier)
    {
        if (!self::isTraceOn()) return false;
        return $carrier
            ? $carrier->pack()
            : false;
    }

    /**
     * Receive the context by point out the way we used to deliver the context
     * @param $carrier \Tricolor\Tracker\Carrier\Base
     * @param boolean $auto_init
     * @return array
     */
    public static function extract($carrier, $auto_init = false)
    {
        if (isset(TraceEnv::$TraceSwitch) && self::forceIsValid(TraceEnv::$TraceSwitch)) {
            if (TraceEnv::$TraceSwitch == TraceEnv::OFF) {
                return array();
            }
        }
        if (!$carrier->unpack()) {
            if ($auto_init) {
                return Tracer::init();
            }
        }
        Context::set(self::RpcId, StrUtils::rpcNext(Context::get(self::RpcId)));
        if (!is_null($force = Context::get(self::TraceSwitch)) && self::forceIsValid($force)) {
            if (TraceEnv::$TraceSwitch == TraceEnv::OFF) {
                return array();
            }
        }
        return Context::toArray();
    }

    /**
     * Transparent transmission
     * Attention/Suggestion:
     *  1. no whitespace is allowed in key, whitespace in key will be converted to '-';
     *  2. both key and value can not be too long, no longer than 255byte will be better;
     *  3. it's recommended to use big-camel-case/upper-camel-case to delimit words in key;
     * @param $key string|array
     * @param $val string|callable
     * @return array
     */
    public static function setContext($key, $val = null)
    {
        if (!self::isTraceOn()) return array();
        $sets = array();
        $transports = is_array($key) ? $key : array($key => $val);
        foreach ($transports as $key => $val) {
            $key = Server::keyFormat(strval($key));
            if (!in_array($key, array(self::TraceId, self::RpcId))) {
                $sets[(string)$key] = self::getTransVal($val);
            }
        }
        empty($sets) OR Context::set($sets);
        return Context::toArray();
    }

    /**
     * Get Values from Context
     * @param $key
     * @return null|string
     */
    public static function getContext($key)
    {
        $context = Context::toArray();
        return isset($context[$key]) ? $context[$key] : null;
    }

    /**
     * Remove the keys you do not want to track
     * @param $_keys string
     * @return array
     */
    public static function untrace($_keys = null)
    {
        if (!self::isTraceOn()) return array();
        $untraces = array_map("strval", func_get_args());
        Context::remove($untraces);
        return Context::toArray();
    }

    /**
     * Remove the keys we do not want to track, except for $_excludes
     * @param $_excludes string
     * @return array
     */
    public static function untraceAll($_excludes = null)
    {
        if (!self::isTraceOn()) return array();
        $traceholds = array_map("strval", func_get_args());
        Context::removeAll($traceholds);
        return Context::toArray();
    }

    /**
     * Use filters to sample.
     * $result = filter1->() & filter2 & filter3...
     * @param $_filters callable|Filter\Base
     */
    public static function logFilter($_filters)
    {
        if (!self::isTraceOn()) return;
        is_array(self::$record_filters) OR (self::$record_filters = array());
        foreach (func_get_args() as $filter) {
            if (is_callable($filter) || ($filter instanceof Filter\Base)) {
                self::$record_filters[] = $filter;
            }
        }
    }

    /**
     * New instance of Tracer
     * @return Tracer
     */
    public static function instance()
    {
        return new Tracer();
    }

    /**
     * Is Trace on?
     * 1. If Context::TraceId is empty. => FALSE
     * 2. If TraceEnv::$TraceForce is not null and valid:
     *      2.1 If it's equal to TraceEnv::ON.  => TRUE
     *      2.2 If it's not equal to TraceEnv::ON.  => FALSE
     * 3. If Context::$TraceForce is not null and valid:
     *      3.1 If it's equal to TraceEnv::ON.  => TRUE
     *      3.2 If it's not equal to TraceEnv::ON.  => FALSE
     * 4. Except for the case before.   => TRUE
     * 5. How to open/close tracking by force:
     *      5.1. Open:
     *          set('TraceSwitch', TraceEnv::ON); or TraceEnv::$TraceSwitch = TraceEnv::ON;
     *      5.2. Close:
     *          set('TraceSwitch', TraceEnv::OFF); or TraceEnv::$TraceSwitch = TraceEnv::OFF;
     * 6. TraceEnv::$$TraceSwitch's priority is higher than Context::$TraceSwitch.
     * 7. Switch is on by default.
     * @return bool
     */
    private static function isTraceOn()
    {
        if (!Context::get(self::TraceId)) {
            return false;
        }
        if (isset(TraceEnv::$TraceSwitch) && self::forceIsValid(TraceEnv::$TraceSwitch)) {
            return TraceEnv::$TraceSwitch == TraceEnv::ON;
        }
        if (!is_null($force = Context::get(self::TraceSwitch)) && self::forceIsValid($force)) {
            return $force == TraceEnv::ON;
        }
        return true;
    }

    /**
     * Is Record on?
     * 1. How to open/close tracking by force:
     *      1.1. Open:
     *          set('LogSwitch', TraceEnv::ON); or TraceEnv::$LogSwitch = TraceEnv::ON;
     *      1.2. Close:
     *          set('LogSwitch', TraceEnv::OFF); or TraceEnv::$LogSwitch = TraceEnv::OFF;
     * 2. TraceEnv::$LogSwitch's priority is higher than Context::$LogSwitch.
     * 3. Switch is on by default.
     * @return bool
     */
    private static function isLogOn()
    {
        if (!self::isTraceOn()) {
            return false;
        }
        if (isset(TraceEnv::$LogSwitch) && self::forceIsValid(TraceEnv::$LogSwitch)) {
            return TraceEnv::$LogSwitch == TraceEnv::ON;
        }
        if (!is_null($force = Context::get(self::LogSwitch)) && self::forceIsValid($force)) {
            return $force == TraceEnv::ON;
        }
        return true;
    }

    /**
     * Is Report on?
     * 1. How to open/close tracking by force:
     *      1.1. Open:
     *          set('CollectSwitch', TraceEnv::ON); or TraceEnv::$CollectSwitch = TraceEnv::ON;
     *      1.2. Close:
     *          set('CollectSwitch', TraceEnv::OFF); or TraceEnv::$CollectSwitch = TraceEnv::OFF;
     * 2. TraceEnv::$CollectSwitch's priority is higher than Context::$CollectSwitch.
     * 3. Switch is on by default.
     * @return bool
     */
    private static function isCollectOn()
    {
        if (!self::isTraceOn()) {
            return false;
        }
        if (isset(TraceEnv::$CollectSwitch) && self::forceIsValid(TraceEnv::$CollectSwitch)) {
            return TraceEnv::$CollectSwitch == TraceEnv::ON;
        }
        if (!is_null($force = Context::get(self::CollectSwitch)) && self::forceIsValid($force)) {
            return $force == TraceEnv::ON;
        }
        return true;
    }

    /**
     * Return TRUE only when it's equal to TraceEnv::ON or TraceEnv::OFF
     * @param $force
     * @return bool
     */
    private static function forceIsValid($force)
    {
       return ($force == TraceEnv::ON) || ($force == TraceEnv::OFF);
    }

    /**
     * Record something what you want.
     * @param $key string
     * @param $val string|callable
     * @param $switch null|boolean|callable($context, $records)
     * @return $this
     */
    public function log($key, $val, $switch = null)
    {
        if (!self::isLogOn()) return $this;
        $this->records[] = array(
            'key' => $key,
            'val' => $val,
            'switch' => $switch
        );
        return $this;
    }

    /**
     * Force to log, ignoring logFilter
     * @param $key string
     * @param $val string|callable
     * @param $switch null|boolean|callable($context, $records)
     * @return $this
     */
    public function forceRecord($key, $val, $switch = null)
    {
        if (!self::isLogOn()) return $this;
        $this->force_records[] = array(
            'key' => $key,
            'val' => $val,
            'switch' => $switch
        );
        return $this;
    }

    /**
     * Attach a tag to data
     * @param $tag string
     * @return $this
     */
    public function tag($tag)
    {
        if (!self::isLogOn()) return $this;
        if ($tag = (string)$tag) {
            $this->record_results['Tag'] = $tag;
        }
        return $this;
    }

    /**
     * Record data, and report result
     * @return bool
     */
    public function run()
    {
        $this->record_results['At'] = Time::get();
        $record_res = $this->doRecord();
        self::isCollectOn() AND Collector::collect($this->getToReport());
        Context::set(self::RpcId, StrUtils::rpcStep(Context::get(self::RpcId)));
        return $record_res;
    }

    /**
     * Merge Context and log result
     * @return array
     */
    private function getToReport()
    {
        return array_merge(Context::toArray(), array_diff($this->record_results, Context::toArray()));
    }

    /**
     * Recording method
     * @return bool
     */
    private function doRecord()
    {
        if (!self::isLogOn()) {
            return false;
        }
        // Force to record
        foreach ($this->force_records as $record) {
            if (!($key = strval($record['key']))) continue;
            if ($this->isSwitchOn($record['switch'])) {
                $this->record_results[$key] = $this->getRecordVal($record['val']);
            }
        }
        // Record filter
        $record_allow = true;
        if (is_array(self::$record_filters)) {
            foreach (self::$record_filters as $filter) {
                if ($this->filterDeny($filter)) {
                    $record_allow = false;
                    break;
                }
            }
        }
        // Record
        if ($record_allow) {
            foreach ($this->records as $record) {
                if (!($key = strval($record['key']))) continue;
                if ($this->isSwitchOn($record['switch'])) {
                    $this->record_results[$key] = $this->getRecordVal($record['val']);
                }
            }
        }
        return true;
    }

    /**
     * Record built-in
     */
    private function builtInLog()
    {
//        $this->log('Ip', Common\Server::getIp(), function ($context) {
//            return isset($context['CloseIp']) && ($context['CloseIp'] == TraceEnv::OFF);
//        });
    }

    /**
     * Record or not, according to the result
     * @param $filter callable|Filter\Base
     * @return bool|null
     */
    private function filterDeny($filter)
    {
        if (($filter instanceof Filter\Base) && !$filter->sample()) {
            return true;
        }
        if (is_callable($filter)) {
            if (!call_user_func($filter, Context::toArray(), $this->record_results)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Executes and returns result
     * @param $switch
     * @return bool
     */
    private function isSwitchOn($switch)
    {
        return is_callable($switch)
            ? (bool)call_user_func($switch, Context::toArray(), $this->record_results)
            : (bool)$switch;
    }

    /**
     * Executes and returns result
     * @param $val
     * @return mixed
     */
    private static function getTransVal($val)
    {
        return (string)$val;
    }

    /**
     * Executes and returns result
     * @param $val
     * @return mixed
     */
    private function getRecordVal($val)
    {
        return is_callable($val)
            ? (string)call_user_func($val, Context::toArray(), $this->record_results)
            : (string)$val;
    }
}