Приклад конфігурації для крону можна знайти в [cron.global.php.dist](/config/autoload/cron.global.php.dist), там
налаштований виклик одного крону `LogCurrentTimeProcessCron`, що сконфігурований
в [log.global.php](/config/autoload/log.global.php). Єдине що він робить при запусці - логує поточну дату. 

Зверніть увагу, на те, що `LogCurrentTimeProcessCron` - запускається в окремому процесі. Це обов'язкова умова.

Більше прикладів різних конфігурацій калбеків для крону

```php
<?php

use rollun\callback\Callback\CronExpression;
use rollun\callback\Callback\Factory\CallbackAbstractFactoryAbstract;
use rollun\callback\Callback\Factory\CronExpressionAbstractFactory;
use rollun\callback\Callback\Factory\MultiplexerAbstractFactory;
use rollun\callback\Callback\Factory\SerializedCallbackAbstractFactory;
use rollun\callback\Callback\Interrupter\Factory\InterruptAbstractFactoryAbstract;
use rollun\callback\Callback\Interrupter\Factory\ProcessAbstractFactory;
use rollun\callback\Callback\Interrupter\Process;
use rollun\callback\Callback\Multiplexer;

return [
    SerializedCallbackAbstractFactory::class => [
        'testCallback1' => 'CallMe::class',
        'testCallback2' => ['service', 'invokableMethod'],
        'testCallback3' => 'CallMe::invokableMethod',
        'testCallback4' => [new TextObject(), 'invokableMethod'],
        'testCallback5' => 'declaredFunction',
        'testCallback6' => function ($value) {
            return $value;
        },
    ],
    CallbackAbstractFactoryAbstract::KEY => [
        'multiplexer' => [
            MultiplexerAbstractFactory::KEY_CLASS => Multiplexer::class,
            MultiplexerAbstractFactory::KEY_CALLBACKS_SERVICES => [
                'callback1ProcessCron',
                'callback2ProcessCron',
                'callback3ProcessCron',
                'callback4ProcessCron',
            ]
        ],
        'callback1ProcessCron' => [
            CronExpressionAbstractFactory::KEY_CLASS => CronExpression::class,
            CronExpressionAbstractFactory::KEY_CALLBACK_SERVICE => 'callback1Process',
            CronExpressionAbstractFactory::KEY_EXPRESSION => '0 6 * * *' // every day at 06:00
        ],
        'callback2ProcessCron' => [
            CronExpressionAbstractFactory::KEY_CLASS => CronExpression::class,
            CronExpressionAbstractFactory::KEY_CALLBACK_SERVICE => 'callback2Process',
            CronExpressionAbstractFactory::KEY_EXPRESSION => '*/30 4-7,14-17 * * *' // 4:00, 4:30, ... 7:30, 14:00, 14:30, ... 17:30

        ],
        'callback3ProcessCron' => [
            CronExpressionAbstractFactory::KEY_CLASS => CronExpression::class,
            CronExpressionAbstractFactory::KEY_CALLBACK_SERVICE => 'callback3Process',
            CronExpressionAbstractFactory::KEY_EXPRESSION => '45 * * * 0' // every Sunday at 00:45, 01:45 ... 23:45
        ],
        'callback4ProcessCron' => [
            CronExpressionAbstractFactory::KEY_CLASS => CronExpression::class,
            CronExpressionAbstractFactory::KEY_CALLBACK_SERVICE => 'callback4Process',
            CronExpressionAbstractFactory::KEY_EXPRESSION => '0 10,12 * * *' // every day at 10:00 and 12:00
        ],
    ],
    InterruptAbstractFactoryAbstract::KEY => [
        'cron' => [
            ProcessAbstractFactory::KEY_CLASS => Process::class,
            ProcessAbstractFactory::KEY_CALLBACK_SERVICE => 'multiplexer',
        ],
        'callback1Process' => [
            ProcessAbstractFactory::KEY_CLASS => Process::class,
            ProcessAbstractFactory::KEY_CALLBACK_SERVICE => 'testCallback4',
        ],
        'callback2Process' => [
            ProcessAbstractFactory::KEY_CLASS => Process::class,
            ProcessAbstractFactory::KEY_CALLBACK_SERVICE => 'testCallback4',
        ],
        'callback3Process' => [
            ProcessAbstractFactory::KEY_CLASS => Process::class,
            ProcessAbstractFactory::KEY_CALLBACK_SERVICE => 'testCallback4',
        ],
        'callback4Process' => [
            ProcessAbstractFactory::KEY_CLASS => Process::class,
            ProcessAbstractFactory::KEY_CALLBACK_SERVICE => 'testCallback5',
        ]
    ],
];
```