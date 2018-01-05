<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 20:48
 */
namespace Tricolor\Tracker\Core;

class Context
{
    public $TraceId;
    public $RpcId;

    /**
     * @var Context
     */
    private static $instance;

    private function __construct()
    {
    }

    /**
     * @return Context
     */
    private static function singleton()
    {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setKey($key, $val)
    {
        $this->$key = $val;
    }

    public function getKey($key)
    {
        return isset($this->$key) ? $this->$key : null;
    }

    public function removeKey($key)
    {
        unset($this->$key);
    }

    public static function get($key)
    {
        return call_user_func(array(self::singleton(), 'getKey'), $key);
    }

    public static function set($key, $val = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                self::set($k, $v);
            }
            return;
        }
        call_user_func(array(self::singleton(), 'setKey'), $key, $val);
    }

    public static function remove($keys)
    {
        $keys = is_array($keys) ? $keys : array($keys);
        $remove_keys = array_diff($keys, array('TraceId', 'RpcId'));
        foreach ($remove_keys as $key) {
            call_user_func(array(self::singleton(), 'removeKey'), $key);
        }
    }

    public static function removeAll($holds)
    {
        $remove_keys = array_diff(array_keys(self::toArray()), $holds);
        empty($remove_keys) OR self::remove($remove_keys);
    }

    public static function toArray()
    {
        return get_object_vars(self::singleton());
    }
}