<?php
namespace Tricolor\Tracker\Filter;

/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 21:01
 */
class Base
{
    public function init()
    {
        return false;
    }

    public function recv()
    {
        return false;
    }

    public function attach()
    {
        return false;
    }
}