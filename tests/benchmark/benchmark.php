<?php
/**
 * User: Tricolor
 * Date: 2017/12/30
 * Time: 13:21
 */
include_once __DIR__ . "/vendor/autoload.php";
include_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . "/config.php";

use Tricolor\Tracker\Test\Libs\BenchmarkStub;

// create datas
// 20.481KB every calls
BenchmarkStub::init();

//$serverFilter = new \Tricolor\Tracker\Sampler\Filter\Server();
//$serverFilter->allow($_SERVER['HTTP_HOST'], '/cat\.zoo\.com/');
//$serverFilter->allow($_SERVER['HTTP_HOST'], '/dog\.zoo\.com/');
//$serverFilter->allow($_SERVER['HTTP_HOST'], '/[a-zA-Z0-9\_]+\.zoo\.com/');
//$serverFilter->deny($_SERVER['REQUEST_URI'], '/feed\/the\/animals/');
//->addAttachments(new \Tricolor\Tracker\Sampler\Attachment\Server($_SERVER))
//    ->addFilters($serverFilter)
//    ->addFilters(new \Tricolor\Tracker\Sampler\Filter\Random(100))
$cycles = 1;
for ($i = 0; $i<$cycles;$i++) {
    $bm = new \Tricolor\Tracker\Core\Benchmark();
    $bm->mark('trace_start');
    \Tricolor\Tracker\Trace::instance()->init()->record(BenchmarkStub::$data);
    // 50 calls ever time
    $times = 50;
    for ($j = 0; $j < $times; $j++) {
        \Tricolor\Tracker\Trace::instance()->record(BenchmarkStub::$data);
    }
    $bm->mark('trace_end');
    $use = $bm->elapsed_time('trace_start', 'trace_end');
    echo "times: " . $times . ", use: " . $use . "s\n";
}
//recv
// 10w times
//