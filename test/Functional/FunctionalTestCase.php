<?php

namespace rollun\test\Functional;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use rollun\dic\InsideConstruct;
use Laminas\ServiceManager\ServiceManager;

/**
 * Functional tests has APP_ENV = 'test' as configured in phpunit.xml
 */
class FunctionalTestCase extends PHPUnitTestCase
{
    private ?ServiceManager $container = null;

    protected function getContainer(): ServiceManager
    {
        if ($this->container === null) {
            $this->container = require __DIR__ . '/../../config/container.php';
            InsideConstruct::setContainer($this->container);
        }

        return $this->container;
    }
}