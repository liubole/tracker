<?php
namespace Tricolor\Tracker\Calculation;
use Tricolor\Tracker\Common\StrUtils;
use Tricolor\Tracker\Context;

/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/9
 * Time: 17:19
 */
class File
{
    public static $cache;

    public function __construct()
    {
        File::$cache = __DIR__ . '/../Cache/';
    }

    public function read()
    {
        if (!is_dir(File::$cache)) return;
        if ($droot = opendir(File::$cache)) {
            while (true) {
                $traceId = readdir($droot);
                if ($traceId === false) {
                    usleep(1000);
                    continue;
                }
                if (!is_dir(File::$cache . "/" . $traceId) || $traceId == "." || $traceId == "..") {
                    continue;
                }
                $contexts = array();
                $dh = dir($traceFile = File::$cache . "/" . $traceId);
                while ($rpcId = $dh->read()) {
                    $fh = fopen($file = File::$cache . "/" . $traceId . '/' . $rpcId, 'rb');
                    $serial = fread($fh, filesize($file));
                    $contexts[$rpcId] = Context::serialToArray($serial);
                    fclose($fh);
                }
                $this->draw($contexts);
                closedir($dh);
                @rmdir($traceFile);
            }
            closedir($droot);
        }
    }

    /**
     * @param $contexts
     * @return array
     */
    public function draw($contexts)
    {
        //todo
        $groups = StrUtils::graph($contexts, $startAt, $traceId);
    }
}