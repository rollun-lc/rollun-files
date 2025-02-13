<?php

use Log\Callback\LogCurrentTimeCallback;
use Log\UseCase\LogDateTime\LogDateTimeHandler;
use Psr\Log\LoggerInterface;
use rollun\callback\Callback\CronExpression;
use rollun\callback\Callback\Factory\CallbackAbstractFactoryAbstract;
use rollun\callback\Callback\Factory\CronExpressionAbstractFactory;
use rollun\callback\Callback\Factory\MultiplexerAbstractFactory;
use rollun\callback\Callback\Factory\SerializedCallbackAbstractFactory;
use rollun\callback\Callback\Interrupter\Factory\InterruptAbstractFactoryAbstract;
use rollun\callback\Callback\Interrupter\Factory\ProcessAbstractFactory;
use rollun\callback\Callback\Interrupter\Process;
use rollun\callback\Callback\Multiplexer;
use rollun\utils\Factory\AbstractServiceAbstractFactory;


return [
    'dependencies' => [
        'abstract_factories' => [
            SerializedCallbackAbstractFactory::class,
            AbstractServiceAbstractFactory::class,
        ],
    ],
    CallbackAbstractFactoryAbstract::KEY => [
        'cron' => [
            MultiplexerAbstractFactory::KEY_CLASS => Multiplexer::class,
            MultiplexerAbstractFactory::KEY_CALLBACKS_SERVICES => [
                'LogCurrentTimeProcessCron',
            ]
        ],
        'LogCurrentTimeProcessCron' => [
            CronExpressionAbstractFactory::KEY_CLASS => CronExpression::class,
            CronExpressionAbstractFactory::KEY_CALLBACK_SERVICE => 'LogCurrentTimeProcess',
            CronExpressionAbstractFactory::KEY_EXPRESSION => '0 6 * * *' // every day at 06:00

        ],
    ],
    InterruptAbstractFactoryAbstract::KEY => [
        'LogCurrentTimeProcess' => [
            ProcessAbstractFactory::KEY_CLASS => Process::class,
            ProcessAbstractFactory::KEY_CALLBACK_SERVICE => 'LogCurrentTimeCallback',
        ]
    ],
    SerializedCallbackAbstractFactory::class => [
        'LogCurrentTimeCallback' => ['LogCurrentTimeCallback', '__invoke'],
    ],
    AbstractServiceAbstractFactory::KEY => [
        'LogCurrentTimeCallback' => [
            AbstractServiceAbstractFactory::KEY_CLASS => LogCurrentTimeCallback::class,
            AbstractServiceAbstractFactory::KEY_DEPENDENCIES => [
                'handler' => LogDateTimeHandler::class,
            ],
        ],
        LogDateTimeHandler::class => [
            AbstractServiceAbstractFactory::KEY_CLASS => LogDateTimeHandler::class,
            AbstractServiceAbstractFactory::KEY_DEPENDENCIES => [
                'logger' => LoggerInterface::class,
            ],
        ],
    ],
];
