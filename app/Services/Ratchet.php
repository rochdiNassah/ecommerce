<?php declare(strict_types=1);

namespace App\Services;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Ratchet implements WampServerInterface
{
    protected $subscribedOrders = [];

    public function onSubscribe(ConnectionInterface $conn, $order)
    {
        echo "Subscribed!\n";
        $this->subscribedOrders[$order->getId()] = $order;
    }

    public function onUnSubscribe(ConnectionInterface $conn, $order)
    {

    }

    public function onOrderEntry($entry)
    {
        $order = (object) json_decode($entry);

        if (!array_key_exists($order->token, $this->subscribedOrders)) {
            return;
        }

        ($this->subscribedOrders[$order->token])->broadcast($order);
    }

    public function onOpen(ConnectionInterface $conn)
    {
         
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        $conn->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $conn->close();
    }

    public function onClose(ConnectionInterface $conn)
    {
        
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Exception cought: {$e->getMessage}\n";

        $conn->close();
    }
}