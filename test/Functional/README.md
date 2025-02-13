Особливість функціональних тестів в тому, що ми можем використовувати повністю налаштований згідно конфігів контейнер.
В тому числі з контейнера можна дістати датастори, чи об'єкт бази данних і звертатись до повноцінної бд.

За потреби також можна повністю підняти застосунок, і передати йому об'єкт запиту, перевіривши, що повернеться у
відповідь. Для цього додайте наступний код до классу FunctionalTestCase. І можете викликати метод `handleRequest`.

```php
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Stream;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

## Add this to FunctionalTestCase class

private ?Application $application = null;

protected function handleRequest(ServerRequestInterface $request): ResponseInterface
{
    error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);
    return $this->getApplication()->handle($request);
}

protected function post(string $uri, ?array $body)
{
    return $this->handleRequest($this->createRequest('POST', $uri, $body));
}

protected function put(string $uri, ?array $body)
{
    return $this->handleRequest($this->createRequest('PUT', $uri, $body));
}

protected function patch(string $uri, ?array $body)
{
    return $this->handleRequest($this->createRequest('PATCH', $uri, $body));
}

protected function get(string $uri, ?array $query = null)
{
    return $this->handleRequest($this->createRequest('GET', $uri, null, $query));
}

private function getApplication(): Application
{
    if ($this->application === null) {
        $this->application = $this->getContainer()->get(Application::class);
        $factory = $this->getContainer()->get(MiddlewareFactory::class);

        // Execute programmatic/declarative middleware pipeline and routing
        // configuration statements
        (require __DIR__ . '/../../config/pipeline.php')($this->application, $factory, $this->getContainer());
        (require __DIR__ . '/../../config/routes.php')($this->application, $factory, $this->getContainer());
    }
    return $this->application;
}

protected function createRequest(string $method, string $uri, ?array $body = null, ?array $queryParams = null): ServerRequestInterface
{
    $request = new ServerRequest(
        [],
        [],
        $uri,
        $method,
        new Stream('php://memory', 'wb+')
    );
    if ($body !== null) {
        $request->getBody()->write(json_encode($body));
    }
    if ($queryParams) {
        $request = $request->withQueryParams($queryParams)
            ->withUri($request->getUri()->withQuery(http_build_query($queryParams)));
    }
    return $request->withHeader('Content-Type', 'application/json');
}
```