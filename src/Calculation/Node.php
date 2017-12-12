<?php
namespace Tricolor\Tracker\Calculation;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/10
 * Time: 0:04
 */
class Node
{
    /**
     * @var Node
     */
    public $head;
    /**
     * @var Node
     */
    public $tail;
    /**
     * @var Node
     */
    public $branch;
    public $rpcId;
    public $context;

    public function __construct($rpcId, $context)
    {
        $this->rpcId = $rpcId;
        $this->context = $context;
    }
}