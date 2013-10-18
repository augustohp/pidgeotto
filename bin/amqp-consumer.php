#!/usr/bin/env php
<?php

require __DIR__.'/../boot.php';

if ($argc != 2) {
    echo "Usage {$argv[0]} <channel>",PHP_EOL;
    exit(2);
}


$channel = $argv[1];
$amqp = new AMQPConnection;
$amqp->setLogin('guest');
$amqp->setPassword('guest');
$amqp->connect();
$amqpChannel = new AMQPChannel($amqp);
$amqpQueue = new AMQPQueue($amqpChannel);
$amqpQueue->setName($channel);
$amqpQueue->setFlags(AMQP_DURABLE);
$amqpQueue->bind('messages', 'routing.key');
$amqpQueue->declareQueue();
while(true) {
    $amqpQueue->consume(function($response, $queue) {
        echo $response->getBody(),PHP_EOL;
        $queue->ack($response->getDeliveryTag());
    });
}
