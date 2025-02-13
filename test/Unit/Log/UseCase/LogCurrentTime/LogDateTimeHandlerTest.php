<?php

namespace rollun\test\Unit\Log\UseCase\LogCurrentTime;

use Log\UseCase\LogDateTime\LogDateTimeHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use DateTimeImmutable;

class LogDateTimeHandlerTest extends TestCase
{
    /**
     * @var LoggerInterface&MockObject|null
     */
    private ?LoggerInterface $logger = null;

    public function testHandleLogsDateTimeValid()
    {
        $format = 'Y-m-d H:i:s';

        $dateTime = new DateTimeImmutable('2025-01-14 12:00:04');
        $this->getLoggerMock()->expects($this->once())
            ->method('info')
            ->with(
                $this->equalTo("DateTime: {currentDate}"),
                $this->equalTo(['currentDate' => $dateTime->format($format)]),
            );

        $handler = new LogDateTimeHandler($this->getLoggerMock());
        $handler->handle($dateTime, $format);
    }

    /**
     * @return LoggerInterface&MockObject
     */
    private function getLoggerMock(): LoggerInterface
    {
        if ($this->logger === null) {
            $this->logger = $this->createMock(LoggerInterface::class);
        }
        return $this->logger;
    }
}