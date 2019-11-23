<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Default RabbitMQ Configuration
    |--------------------------------------------------------------------------
    |
    | Here is where you can register RabbitMQ connection for your application. These
    | configuration are loaded by the injection RabbitMQ within an instance which
    | is assigned the "rabbitmq" instance. Enjoy building your RabbitMQ!
    |
    */
    'rabbitmq' => [
        'host'          => getenv('RABBITMQ_HOST', '192.168.1.10'),
        'port'          => getenv('RABBITMQ_PORT', 5672),
        'username'      => getenv('RABBITMQ_USERNAME', 'rabbitmq'),
        'password'      => getenv('RABBITMQ_PASSWORD', '123456'),
        'vhost'         => getenv('RABBITMQ_VHOST', 'rabbitmq'),
        'insist'        => false,
        'loginMethod'   => 'AMQPLAIN',
        'loginResponse' => null,
        'locale'        => 'zh_CN',
    ],
];