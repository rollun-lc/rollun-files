<?php

declare(strict_types=1);

use Laminas\Stdlib\ArrayUtils\MergeRemoveKey;
use Psr\Log\LoggerInterface;
use rollun\logger\Writer\Stream;

return [
    'log' => [
        LoggerInterface::class => [
            'writers' => [
                'udp_logstash' => new MergeRemoveKey(),
                'local_file' => [
                    'name' => Stream::class,
                    'options' => [
                        'stream' => 'data/logs/all.log',
                    ]
                ],
            ],
        ],
    ],
];