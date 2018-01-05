<?php
/**
 * User: Tricolor
 * Date: 2018/1/5
 * Time: 14:55
 */
namespace Tricolor\Tracker\Collector;

use Tricolor\Tracker\Config\Collector;

class FileLog
{
    public static function write($message)
    {
        if (!($file = self::fileMake())) {
            return false;
        }
        return file_put_contents($file, $message . "\n", FILE_APPEND);
    }

    private static function fileMake()
    {
        $root = Collector\FileLog::$root;
        if (!$root) {
            return false;
        }
        if (!is_dir($root) AND !mkdir($root, 766, true)) {
            return false;
        }
        $logname = Collector\FileLog::$log_name;
        $file = rtrim($root, '/') . '/' . ($logname ? $logname : "trace.log");
        if (file_exists($file) OR touch($file)) {
            return $file;
        }
        return false;
    }

    public function logger($fileName)
    {
        $fileHandle = fopen($fileName, 'a');
        while (true) {
            fwrite($fileHandle, yield . "\n");
        }
    }
}