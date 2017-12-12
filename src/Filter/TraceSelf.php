<?php
namespace Tricolor\Tracker\Filter;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/9
 * Time: 17:05
 */
class TraceSelf extends Base
{
    public function recv()
    {
        return false;
    }

    public function attach()
    {
        return false;
    }
}