<?php
namespace Tricolor\Tracker\Common;

/**
 * Created by PhpStorm.
 * User: Tricolor
 * Date: 2017/12/9
 * Time: 20:46
 */
class StrUtils
{
    public static function startsWith($haystack, $needle)
    {
        return strpos($haystack, $needle) === 0;
    }

    public static function rpcStep(&$str)
    {
        if (!isset($str) || strlen($str) === 0) {
            return $str = '';
        }
        if (($pos = strrpos($str, '.')) === false) {
            return $str = (string)($str + 1);
        }
        return $str = substr($str, 0, $pos + 1) . ((int)substr($str, $pos + 1) + 1);
    }

    public static function rpcNext(&$str)
    {
        return $str .= '.0';
    }

    public static function rpcInit(&$str)
    {
        return $str = '0';
    }

//    /**
//     * 提取点 & 分组 & 组内排序
//     * @param $contexts
//     * @param $startAt
//     * @param $traceId
//     * @return array
//     */
//    public static function graph(&$contexts, &$startAt, &$traceId)
//    {
//        $startAt = $traceId = null;
//        $groups = array();
//        foreach ($contexts as $rpcId => $context) {
//            $groups[self::group($rpcId)][self::order($rpcId)] = self::point($context);
//            $startAt = isset($startAt) ? min($startAt, $context['At']) : $context['At'];
//            isset($traceId) OR ($traceId = $context['TraceId']);
//        }
//        // 返回点集合 & 关系集合
//        foreach ($groups as $gid => $group) {
//            if (($count = count($group)) > 1) {
//                $i = 0;
//                foreach ($group as $point) {
//                    if ($i >= $count - 1) {
//                        break;
//                    }
//                    $i++;
//                }
//            }
//
//        }
//
//        return array($points, $graph);
//    }
//
//    private static function order($rpcId)
//    {
//        if (($pos = strrpos($rpcId, '.')) === false) return (int)$rpcId;
//        return (int)substr($rpcId, $pos + 1);
//    }
//
//    private static function group($rpcId)
//    {
//        if (($pos = strrpos($rpcId, '.')) === false) return '.';
//        return substr($rpcId, 0, $pos + 1);
//    }
//
//    private static function point($context)
//    {
//        return array(
//            'x' => $context['At'],
//            'y' => substr_count($context['RpcId'], '.'),
//            'rpcId' => $context['RpcId'],
//            'at' => $context['At'],
//            'traceId' => $context['TraceId'],
//            'tag' => $context['TAG'],
//        );
//    }
//
//    public static function tree(&$contexts)
//    {
//        $chains = array();
//        foreach ($contexts as $rpcId => $context) {
//            StrUtils::insert($chains, $rpcId, $contexts);
//        }
//        return $chains['0'];
//    }
//
//    /**
//     * @param $chains
//     * @param $rpcId
//     * @param $contexts
//     * @return Node
//     */
//    public static function &insert(&$chains, $rpcId, &$contexts)
//    {
//        if (isset($chains[$rpcId])) return $chains[$rpcId];
//        isset($contexts[$rpcId]) OR ($contexts[$rpcId] = null);
//        if (($backRpcId = StrUtils::stepBack($rpcId)) !== false) {
//            $head = &StrUtils::insert($chains, $backRpcId, $contexts);
//            $chains[$backRpcId] = $head;
//
//            $var = StrUtils::levelSame($rpcId, $backRpcId) ? 'tail' : 'branch';
//            $node = new Node($rpcId, $contexts[$rpcId]);
//            $node->head = $head;
//            $head->$var = $node;
//            return $node;
//        } else {
//            $root = new Node($rpcId, $contexts[$rpcId]);
//            return $root;
//        }
//    }
//
//    public static function stepBack($str)
//    {
//        if (!isset($str) || strlen($str) === 0) return false;
//        if ($str == '0') return false;
//        if (($pos = strrpos($str, '.')) === false) return (string)($str - 1);
//        if ($str[strlen($str) - 1] == '0') return substr($str, 0, $pos);
//        return substr($str, 0, $pos + 1) . (substr($str, $pos + 1) - 1);
//    }
//
//    public static function levelSame($rpcId, $rpcId2)
//    {
//        return substr_count($rpcId, '.') === substr_count($rpcId2, '.');
//    }

}