<?php

use rollun\service\Inventory\Infrastructure\Adapters\EbayOpenApi\Sender;
use TaxonomyAPI\OpenAPI\V1_0_0\Client\Rest\CategoryTree;

ini_set('memory_limit', '1G');
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);
//Deprecated::$enabled = false;

// Change to the project root, to simplify resolving paths

chdir(dirname(__DIR__, 1));
require 'vendor/autoload.php';

$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);
$yourApiKey = getenv('YOUR_API_KEY');
$client = OpenAI::client($yourApiKey);

$result = $client->chat()->create([
    'model' => 'gpt-4',
    'messages' => [
        ['role' => 'user', 'content' => 'Hello!'],
    ],
]);

echo $result->choices[0]->message->content; // Hello! How can I assist you today?