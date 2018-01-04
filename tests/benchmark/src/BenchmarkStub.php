<?php
/**
 * User: Tricolor
 * Date: 2017/12/30
 * Time: 14:00
 */
namespace Tricolor\Tracker\Test\Libs;

class BenchmarkStub
{
    public static $data;

    public static function init()
    {
        $data = array();
        for($i = 1; $i <= 10; $i++) {
            $data[] = array(
                'key' . $i => Utils::randStr('alpha', 8),
                'val' . $i => Utils::randStr('alpha', 2000),
            );
        }
        self::$data = array(
            'state' => 1,
            'mess'  => 'This is test data.',
            'data'  => $data,
        );
    }

}