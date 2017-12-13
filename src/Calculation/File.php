<?php
namespace Tricolor\Tracker\Calculation;
use Tricolor\Tracker\Common\StrUtils;
use Tricolor\Tracker\Config;
use Tricolor\Tracker\Context;

/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/9
 * Time: 17:19
 */
class File
{
    public function __construct()
    {

    }

    public function read()
    {
        if (!is_dir(Config::$cacheDir)) return;
        if ($droot = opendir(Config::$cacheDir)) {
            while (true) {
                $traceId = readdir($droot);
                if ($traceId === false) {
                    usleep(1000);
                    continue;
                }
                if (!is_dir(Config::$cacheDir . "/" . $traceId) || $traceId == "." || $traceId == "..") {
                    continue;
                }
                $contexts = array();
                $dh = dir($traceFile = Config::$cacheDir . "/" . $traceId);
                while ($rpcId = $dh->read()) {
                    $fh = fopen($file = Config::$cacheDir . "/" . $traceId . '/' . $rpcId, 'rb');
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