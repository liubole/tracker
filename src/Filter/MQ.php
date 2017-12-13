<?php
namespace Tricolor\Tracker\Filter;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/9
 * Time: 17:05
 */
class MQ extends Base
{
    public function recv()
    {
        return true;
    }

    public function attach()
    {
        return true;
    }
}