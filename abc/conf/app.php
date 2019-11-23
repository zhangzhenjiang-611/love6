<?php
return [
    /**
     * Redis configuration.
     */
    'redis' => [],

    /**
     * Database configuration.
     */
    'database' => env('DEFAULT_DATABASE', 'database'),

    /**
     * RabbitMQ configuraton.
     */
    'rabbitmq' => env('DEFAULT_RABBITMQ', 'rabbitmq'),

    /**
     * 不同私有云商家队列配置（如果后期商家过多，可以采用数据库的方式）。
     */
    'sync_queue' => [
        // 厉害了我的哥 对应 佳一私库
        'a7e431fbeb2b805b38180cd2ca8d1d27' => [
            // 直接连数据库写入【使用中】
            'host' => '192.168.1.177',
            'port' => '3306',
            'user' => 'jyzhiying',
            'pwd'  => 'fntysIV3et2BdqavsHeq3Idnh4rDjXyvyWqO2ppsppWHYbyHmJq1zYeiqYuaim67mIeae7eIr9iEoHp2716suPrUDogOo6oJXjxCyInZk5KIG96x',
            'dbname' => 'jiayi',
            // 队列方式写入【未来优化】
            'type'     => 'topic',
            'queue'    => 'queue.jiayi',
            'exchange' => 'amq.topic',
        ],
    ]
];