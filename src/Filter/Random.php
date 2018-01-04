<?php
/**
 * User: Tricolor
 * Date: 2017/12/17
 * Time: 14:37
 */
namespace Tricolor\Tracker\Filter;
use Tricolor\Tracker\Config\Record;

class Random implements Base
{
    private $rate = null;

    public function __construct($rate)
    {
        $this->rate = $rate;
    }

    public function sample()
    {
        return rand(1, 100) <= (isset($this->rate) ? (int)$this->rate : (int)Record::$randomFilterRate);
    }
}