<?php

namespace Log\Callback;


use Log\UseCase\LogDateTime\LogDateTimeHandler;
use rollun\dic\InsideConstruct;

class LogCurrentTimeCallback
{
    public function __construct(private LogDateTimeHandler $handler)
    {
    }

    public function __wakeup(): void
    {
        InsideConstruct::initWakeup([
            'handler' => LogDateTimeHandler::class,
        ]);
    }

    public function __sleep(): array
    {
        return [];
    }

    public function __invoke(): void
    {
        $this->handler->handle(new \DateTimeImmutable());
    }
}