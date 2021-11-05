<?php declare(strict_types=1);

namespace App\Services;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Ratchet implements WampServerInterface
{
    /** @var array */
    protected $subscribedOrders = [];

    /**
     * @param  \Ratchet\ConnectionInterface  $conn
     * @param  object  $order
     * @return void
     */
    public function onSubscribe(ConnectionInterface $conn, $order): void
    {
        $this->subscribedOrders[$order->getId()] = $order;
    }

    /**
     * @param  \Ratchet\ConnectionInterface  $conn
     * @param  object  $order
     * @return void
     */
    public function onUnSubscribe(ConnectionInterface $conn, $order): void
    {

    }

    /**
     * @param  string  $event
     * @return void
     */
    public function onOrderEntry($event): void
    {
        $order = (object) json_decode($event);

        if (!array_key_exists($order->token, $this->subscribedOrders)) {
            return;
        }

        ($this->subscribedOrders[$order->token])->broadcast($order);
    }

    /**
     * @param  \Ratchet\ConnectionInterface  $conn
     * @return void
     */
    public function onOpen(ConnectionInterface $conn): void
    {
         
    }

    /**
     * Fired when the application receives an RPC.
     * 
     * @param  \Ratchet\ConnectionInterface  $conn
     * @param  int  $id
     * @param  string  $topic
     * @param  array  $params
     * @return void
     */
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params): void
    {
        $conn->close();
    }

    /**
     * @param  \Ratchet\ConnectionInterface  $conn
     * @param  string  $topic
     * @param  string  $event
     * @param  array  exclude
     * @param  array  $eligible
     * @return void
     */
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible): void
    {
        $conn->close();
    }

    /**
     * @param  \Ratchet\ConnectionInterface  $conn
     * @return void
     */
    public function onClose(ConnectionInterface $conn): void
    {
        
    }

    /**
     * @param  \Ratchet\ConnectionInterface  $conn
     * @param  \Exception  $e
     * @return void
     */
    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        echo sprintf('\n\nException cought: %s\n\n', $e->getMessage);

        $conn->close();
    }
}