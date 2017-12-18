<?php
namespace Tricolor\Tracker\Common;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/18 18:42
 */
class Ip
{
    public static function getIp($server = null)
    {
        $server = $server ? $server : $_SERVER;
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
}