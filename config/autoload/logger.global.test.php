<?php

use Laminas\Stdlib\ArrayUtils\MergeRemoveKey;
use Psr\Log\LoggerInterface;

return [
    'log' => [
        LoggerInterface::class => [
            'writers' => [
                'stream_stdout' => new MergeRemoveKey()
            ],
        ],
    ],
];