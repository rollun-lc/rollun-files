<?php

namespace rollun\test\Unit\Log\Callback;

use Log\Callback\LogCurrentTimeCallback;
use Log\UseCase\LogDateTime\LogDateTimeHandler;
use PHPUnit\Framework\TestCase;

class LogCurrentTimeCallbackTest extends TestCase
{
    public function setUp(): void
    {
        $this->handler = $this->createMock(LogDateTimeHandler::class);
        $this->callback = new LogCurrentTimeCallback($this->handler);
    }

    public function testInvoke()
    {
        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->isInstanceOf(\DateTimeImmutable::class));

        call_user_func($this->callback);
    }
}