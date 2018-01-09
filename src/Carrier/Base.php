<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 22:04
 */
namespace Tricolor\Tracker\Carrier;

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