<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Wire\AMQPTable;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class receiveDirectMessage
{
  public function __construct($message = null)
  {
    $this->connection = new AMQPStreamConnection('192.168.0.189', 5672, 'admin', 'admin');
    $this->channel = $this->connection->channel();
    $this->message = $message;
  }

  public function receive()
  {
    $this->channel->queue_declare('direct_queue', false, false, false, false);

    echo '[*] Waiting for messages.';

    $callback = function ($msg) {
      echo '[*] Received : ' . $msg->body . "\n";
    };

    $this->channel->basic_consume('direct_queue', '', false, true, false, false, $callback);

    while ($this->channel->is_consuming()) {
      $this->channel->wait();
    }

    $this->channel->close();
    $this->connection->close();
  }
}

$receiveDirectMessage = new receiveDirectMessage();
$receiveDirectMessage->receive();
