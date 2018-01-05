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

class Trace
{
    /**
     * Records
     * @var array
     */
    private $records;

    /**
     * Records (ignore record filter)
     * @var array
     */
    private $force_records;

    /**
     * Record filters array
     * @var array \Tricolor\Tracker\Filter\Base
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

    public function __construct()
    {
        $this->records = array();
        $this->force_records = array();
        $this->record_results = array();
        $this->defaultRecord();
    }

    /**
     * Initialization the context
     * @return array
     */
    public static function init()
    {
        if (isset(TraceEnv::$TraceForce) && (TraceEnv::$TraceForce == TraceEnv::OFF)) {
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
     * @param $deliverer \Tricolor\Tracker\Deliverer\Base
     * @return boolean
     */
    public static function transBy($deliverer)
    {
        if (!self::isTraceOn()) return false;
        return $deliverer
            ? $deliverer->pack()
            : false;
    }

    /**
     * Receive the context by point out the way we used to deliver the context
     * @param $from_deliverer \Tricolor\Tracker\Deliverer\Base
     * @param boolean $auto_init
     * @return array
     */
    public static function buildFrom($from_deliverer, $auto_init = false)
    {
        if (isset(TraceEnv::$TraceForce) && self::forceValid(TraceEnv::$TraceForce)) {
            if (TraceEnv::$TraceForce == TraceEnv::OFF) {
                return array();
            }
        }
        if (!$from_deliverer->unpack()) {
            if ($auto_init) {
                return Trace::init();
            }
        }
        Context::set(self::RpcId, StrUtils::rpcNext(Context::get(self::RpcId)));
        if (!is_null($force = Context::get('TraceForce')) && self::forceValid($force)) {
            if (TraceEnv::$TraceForce == TraceEnv::OFF) {
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
    public static function transport($key, $val = null)
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
    public static function recordFilter($_filters)
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
     * New instance of Trace
     * @return Trace
     */
    public static function instance()
    {
        return new Trace();
    }

    /**
     * Is Trace on?
     * @return bool
     */
    private static function isTraceOn()
    {
        if (!Context::get(self::TraceId)) {
            return false;
        }
        if (isset(TraceEnv::$TraceForce) && self::forceValid(TraceEnv::$TraceForce)) {
            return TraceEnv::$TraceForce == TraceEnv::ON;
        }
        if (!is_null($force = Context::get('TraceForce')) && self::forceValid($force)) {
            return $force == TraceEnv::ON;
        }
        return true;
    }

    /**
     * Is Record on?
     * @return bool
     */
    private static function isRecordOn()
    {
        if (!self::isTraceOn()) {
            return false;
        }
        if (isset(TraceEnv::$RecordForce) && self::forceValid(TraceEnv::$RecordForce)) {
            return TraceEnv::$RecordForce == TraceEnv::ON;
        }
        if (!is_null($force = Context::get('RecordForce')) && self::forceValid($force)) {
            return $force == TraceEnv::ON;
        }
        return true;
    }

    /**
     * Is Trace on?
     * FALSE:
     *      1. If Context::TraceId is empty
     *      2. If TraceEnv::$ReportForce is valid, and it's not equal to TraceEnv::ON
     *      3. If TraceEnv::$ReportForce is valid, and it's not equal to TraceEnv::ON
     * TRUE:
     *      1. Except for the case before
     * @return bool
     */
    private static function isReportOn()
    {
        if (!self::isTraceOn()) {
            return false;
        }
        if (isset(TraceEnv::$ReportForce) && self::forceValid(TraceEnv::$ReportForce)) {
            return TraceEnv::$ReportForce == TraceEnv::ON;
        }
        if (!is_null($force = Context::get('ReportForce')) && self::forceValid($force)) {
            return $force == TraceEnv::ON;
        }
        return true;
    }

    /**
     * Return TRUE only when it's equal to TraceEnv::ON or TraceEnv::OFF
     * @param $force
     * @return bool
     */
    private static function forceValid($force)
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
    public function record($key, $val, $switch = null)
    {
        if (!self::isRecordOn()) return $this;
        $this->records[] = array(
            'key' => $key,
            'val' => $val,
            'switch' => $switch
        );
        return $this;
    }

    /**
     * Force to record, ignoring recordFilter
     * @param $key string
     * @param $val string|callable
     * @param $switch null|boolean|callable($context, $records)
     * @return $this
     */
    public function forceRecord($key, $val, $switch = null)
    {
        if (!self::isRecordOn()) return $this;
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
        if (!self::isRecordOn()) return $this;
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
        $record_res = $this->doRecord();
        $this->record_results['At'] = Time::get();
        self::isReportOn() AND Collector::report($this->getToReport());
        Context::set(self::RpcId, StrUtils::rpcStep(Context::get(self::RpcId)));
        return $record_res;
    }

    /**
     * Merge Context and record result
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
        if (!self::isRecordOn()) {
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
    private function defaultRecord()
    {
//        $this->record('Ip', Common\Server::getIp(), function ($context) {
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
        if (is_callable($filter)
            && !call_user_func($filter, Context::toArray(), $this->record_results)) {
            return true;
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
        return is_bool($switch)
            ? $switch
            : (is_callable($switch)
                ? (bool)call_user_func($switch, Context::toArray(), $this->record_results)
                : true);
    }

    /**
     * Executes and returns result
     * @param $val
     * @return mixed
     */
    private static function getTransVal($val)
    {
        return is_callable($val) ? call_user_func($val, Context::toArray()) : $val;
    }

    /**
     * Executes and returns result
     * @param $val
     * @return mixed
     */
    private function getRecordVal($val)
    {
        return is_callable($val)
            ? call_user_func($val, Context::toArray(), $this->record_results)
            : $val;
    }
}