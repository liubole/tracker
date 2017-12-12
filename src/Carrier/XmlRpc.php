<?php
namespace Tricolor\Tracker\Carrier;
use Tricolor\Tracker\Context;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/9
 * Time: 16:49
 */
class XmlRpc extends Base
{
    private static $key = '__trace__';

    public function unpack(&$post)
    {
        //todo
        return $post;
    }

    public function pack(&$post)
    {
        //todo
        return $post;
    }
}