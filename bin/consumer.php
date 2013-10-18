#!/usr/bin/env php
<?php

require __DIR__.'/../boot.php';

$redis = new Redis;
$redis->connect('127.0.0.1');

function printMessage($redis, $channel, $message)
{
    echo $message,PHP_EOL;
}

if ($argc != 2) {
    echo "Usage: {$argv[0]} <channel>",PHP_EOL;
    exit(2);
}

$channel = $argv[1];
$redis->subscribe([$channel], 'printMessage');
