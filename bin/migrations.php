<?php
/**
 * НЕ ЗАПУСКАТИ НА ПРОДАКШЕНІ
 * Запукає файли міграції.
 */

use Laminas\Db\Adapter\Adapter;
use Psr\Container\ContainerInterface;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

// Список шляхів до файлів міграції, що треба запустити
$migrations = [
    'migrations/1-create-test-table.sql'
];

/** @var ContainerInterface $container */
$container = require 'config/container.php';

$appEnv = getenv('APP_ENV');
if (!in_array($appEnv, ['dev', 'test'])) {
    echo 'Migrations can be run only in development mode.' . PHP_EOL;
    exit();
}

/** @var Adapter $db */
$db = $container->get('db');

foreach ($migrations as $migration) {
    $db->query(file_get_contents($migration), Adapter::QUERY_MODE_EXECUTE);
}