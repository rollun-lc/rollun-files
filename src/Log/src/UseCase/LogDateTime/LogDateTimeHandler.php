<?php

namespace Log\UseCase\LogDateTime;


use DateTimeImmutable;
use Psr\Log\LoggerInterface;

class LogDateTimeHandler
{
    public const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function handle(
        DateTimeImmutable $dateTime,
        string $format = self::DEFAULT_DATE_FORMAT
    ): void
    {
        $this->logger->info('DateTime: {currentDate}', [
            'currentDate' => $dateTime->format($format)
        ]);
    }
}