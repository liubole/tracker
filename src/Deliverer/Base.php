<?php
namespace Tricolor\Tracker\Deliverer;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 22:04
 */
interface Base
{
    /**
     * @return bool
     */
    public function unpack();
    /**
     * @return bool
     */
    public function pack();
}