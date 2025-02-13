<?php
/**
 * @copyright Copyright © 2014 Rollun LC (http://rollun.com/)
 * @license LICENSE.md New BSD License
 */

declare(strict_types=1);

use Jaeger\Span\Context\SpanContext;
use Jaeger\Tag\StringTag;
use rollun\dic\InsideConstruct;
use rollun\logger\LifeCycleToken;
use rollun\utils\Json\Serializer;
use Laminas\Diactoros\ServerRequestFactory;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Laminas\ServiceManager\ServiceManager;

ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_USER_DEPRECATED);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
(function () {
    /** @var ServiceManager $container */
    $container = require 'config/container.php';

    InsideConstruct::setContainer($container);


    /** @var Application $app */
    $app = $container->get(Application::class);
    $factory = $container->get(MiddlewareFactory::class);

    // Execute programmatic/declarative middleware pipeline and routing
    // configuration statements
    (require 'config/pipeline.php')($app, $factory, $container);
    (require 'config/routes.php')($app, $factory, $container);

    $app->run();
})();
