<?php

require '../boot.php';

use Respect\Rest\Router;

$persistence = new Redis;
$persistence->connect('127.0.0.1');
$router = new Router;
$woo = $router->get('/', 'Hi');
$router->post('/message/*', function($topic) use($persistence) {
    if (isset($_POST['message']) === false) {
        header('HTTP/1.1 400 Missing message.');
        return 'Missing message';
    }

    $message = filter_input(INPUT_POST, 'message');
    $clientsWhoListened = $persistence->publish($topic, $message);
    if ($clientsWhoListened > 0 ) {
        return header('HTTP/1.1 201 Message received.');
    }

    header('HTTP/1.1 500 Message lost');
});

