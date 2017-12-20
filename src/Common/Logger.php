<?php
namespace Tricolor\Tracker\Common;
use Tricolor\Tracker\Config\Debug;

/**
 *
 * User: Tricolor
 * DateTime: 2017/12/20 15:52
 */
class Logger
{
    private static $file_ext = 'log';
    private static $date_fmt = 'Y-m-d H:i:s';
    private static $file_permissions = '0644';

    public static function log($level, $msg)
    {
        if (Debug::$debug === false || !in_array($level, Debug::$thresholds)) {
            return false;
        }
        $log_root = is_callable(Debug::$logRoot) ? call_user_func(Debug::$logRoot) : (string)Debug::$logRoot;

        $file_path = rtrim($log_root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'log-' . date('Y-m-d') . '.' . Logger::$file_ext;
        $message = '';

        if (!file_exists($file_path)) $new_file = true;

        if (!$fp = @fopen($file_path, 'ab')) return false;

        $date = date(Logger::$date_fmt);
        $message .= $level . ' - ' . $date . ' --> ' . $msg . "\n";

        flock($fp, LOCK_EX);
        for ($written = 0, $length = strlen($message); $written < $length; $written += $result) {
            if (($result = fwrite($fp, substr($message, $written))) === false) {
                break;
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        if (isset($new_file) && $new_file) {
            chmod($file_path, Logger::$file_permissions);
        }

        return isset($result) && is_int($result);
    }
}