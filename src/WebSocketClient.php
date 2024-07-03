<?php

namespace GeSign;

use WebSocket\Client;

class WebSocketClient
{
    private $client;

    public function __construct($url)
    {
        $this->client = new Client($url);
    }

    public function sendMessage($message)
    {
        $this->client->send($message);
    }

    public function receiveMessage()
    {
        return $this->client->receive();
    }

    public function close()
    {
        $this->client->close();
    }
}
