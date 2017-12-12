<?php
namespace Tricolor\Tracker\Common;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 20:57
 */
class UID
{
    public static function create()
    {
        return strtolower(uniqid(md5(php_uname('n')).'-', true));
    }
}