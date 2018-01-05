<?php
/**
 * User: Tricolor
 * Date: 2018/1/4
 * Time: 21:22
 */
namespace Tricolor\Tracker\Demo;
use Tricolor\Tracker\Common\Server;
use Tricolor\Tracker\Filter\Random;
use Tricolor\Tracker\Filter\Simple;
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Config\Collector;

class Client extends Controller
{
    public function __construct()
    {
        define('CLIENTID', 'Client');
        parent::__construct();
        // config
        Collector::$reporter = function ($info) {
            $call = array('\Tricolor\Tracker\Demo\Logger', 'write');
            call_user_func_array($call, array('./logs/', $info));
        };
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        Trace::init();
        Trace::transport('AtH', microtime());
        Trace::transport('InAt', microtime());
        Trace::transport('UsrIp', Server::getIp());
        Trace::transport($s = 'T-a', $s);
        Trace::transport($s = 'T_a', $s);
        Trace::transport($s = 'a-b', $s);
        Trace::transport($s = 'a_b', $s);
        Trace::transport($s = 'a b', $s);
        Trace::recordFilter(
            new Random(100),
            (new Simple())->allow($host, '/https?:\/\/filter\.tracker\.tricolor\.com/')
        );
        Trace::instance()->tag('Init')->run();
    }

    public function touch()
    {
        /**
         * @var $rpc \Tricolor\Tracker\Demo\Rpc
         */
        $rpc = $this->load('\Tricolor\Tracker\Demo\Rpc', 'rpc');
        $res = $rpc->exec('touch', array(
            'time' => time(),
            'randStr' => Utils::randStr('alpha', rand(1000, 20000))
        ));
        $this->output($res);
    }
}

