<?php
/**
 * User: Tricolor
 * Date: 2017/12/28
 * Time: 9:59
 */
namespace Tricolor\Tracker\Config;

class TraceEnv
{
    const ON = 16;
    const OFF = 32;

    /**
     * @var null|integer
     */
    public static $TraceForce = null;

    /**
     * @var null|integer
     */
    public static $RecordForce = null;

    /**
     * @var null|integer
     */
    public static $ReportForce = null;
}