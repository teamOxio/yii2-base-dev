<?php

use yii\queue\amqp_interop\Queue;

return [
    'class' => Queue::class,
    'host' => '',
    'port' => 5672,
    'user' => '',
    'vhost' => '',
    'password' => '',
    'queueName' => 'main',
    'driver' => Queue::ENQUEUE_AMQP_LIB,
];
