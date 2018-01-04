<?php
/**
 * User: Tricolor
 * Date: 2018/1/3
 * Time: 14:55
 */
namespace Tricolor\Tracker\Common;

class Server
{
    public static function getIp($server = null)
    {
        isset($server) OR ($server = $_SERVER);
        $ip = FALSE;
        if (isset($server["HTTP_CLIENT_IP"]) && !empty($server["HTTP_CLIENT_IP"])) {
            $ip = $server["HTTP_CLIENT_IP"];
        }
        if (isset($server['HTTP_X_FORWARDED_FOR']) && !empty($server['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(", ", $server['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = FALSE;
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!preg_match("/^(10|172\.16|192\.168)\./", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        if ($ip) return $ip;
        return isset($server['REMOTE_ADDR']) ? $server['REMOTE_ADDR'] : FALSE;
    }

    public static function getUa($server = null)
    {
        isset($server) OR ($server = $_SERVER);
        return isset($server['HTTP_USER_AGENT']) ? $server['HTTP_USER_AGENT'] : '';
    }

    public static function getRemoteAddr($server = null)
    {
        isset($server) OR ($server = $_SERVER);
        return isset($server['REMOTE_ADDR']) ? $server['REMOTE_ADDR'] : '';
    }

    public static function getXForwardFor($server = null)
    {
        isset($server) OR ($server = $_SERVER);
        return isset($server['HTTP_X_FORWARDED_FOR']) ? $server['HTTP_X_FORWARDED_FOR'] : '';
    }

    public static function getHost($server = null)
    {
        isset($server) OR ($server = $_SERVER);
        return isset($server['HTTP_HOST']) ? $server['HTTP_HOST'] : '';
    }

    public static function getReqUri($server = null)
    {
        isset($server) OR ($server = $_SERVER);
        return isset($server['REQUEST_URI']) ? $server['REQUEST_URI'] : '';
    }

    public static function getQuery($server = null)
    {
        isset($server) OR ($server = $_SERVER);
        return isset($server['QUERY_STRING']) ? $server['QUERY_STRING'] : '';
    }
}