<?php
/**
 * User: Tricolor
 * Date: 2017/12/30
 * Time: 15:24
 */
namespace Tricolor\Tracker\Collector;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Tricolor\Tracker\Config\Collector\RabbitMQ as RabbitConfig;

class RabbitMQ
{
    private static $conn;

    /**
     * @param $message mixed
     * @throws
     */
    public static function pub($message)
    {
        try {
            $conn = self::getConn();
            $channel = self::getChannel($conn, self::exchange());
            if ($conn && $channel) {
                $msgObj = new AMQPMessage($message);
                $channel->basic_publish($msgObj, self::exchange(), self::key());
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $callback
     * @throws
     */
    public static function sub($callback)
    {
        try {
            $conn = self::getConn();
            $channel = self::getChannel($conn, self::exchange());
            $channel->queue_declare(self::queue(), false, false, false, false);
            $channel->queue_bind(self::queue(), self::exchange(), self::key());
            $channel->basic_consume(self::queue(), '', false, true, false, false, $callback);
            while (count($channel->callbacks)) {
                $channel->wait();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $conn AMQPStreamConnection
     * @param $exchange
     * @return null|AMQPChannel
     */
    private static function getChannel($conn, $exchange)
    {
        if ($conn) {
            $channel = $conn->channel($conn->get_free_channel_id());
            $channel->exchange_declare($exchange, 'topic', false, true, false);
            return $channel;
        }
        return null;
    }

    /**
     * @return AMQPStreamConnection
     * @throws $e
     */
    private static function getConn()
    {
        if (!self::$conn) {
            try {
                $config = self::getConfig();
                self::$conn = new AMQPStreamConnection(
                    $config['host'],
                    $config['port'],
                    $config['user'],
                    $config['password'],
                    $config['vhost'],
                    $config['insist'],
                    $config['login_method'],
                    $config['login_response'],
                    $config['locale'],
                    $config['connection_timeout'],
                    $config['read_write_timeout'],
                    $config['context'],
                    $config['keepalive'],
                    $config['heartbeat']);
            } catch (\Exception $e) {
                throw $e;
            }
        }
        return self::$conn;
    }

    /**
     * @return string
     */
    private static function exchange()
    {
        return RabbitConfig::$exchange;
    }

    /**
     * @return string
     */
    private static function queue()
    {
        return RabbitConfig::$queue;
    }

    /**
     * @return string
     */
    private static function key()
    {
        return RabbitConfig::$key;
    }

    /**
     * @return array
     */
    private static function getConfig()
    {
        return RabbitConfig::get();
    }
}