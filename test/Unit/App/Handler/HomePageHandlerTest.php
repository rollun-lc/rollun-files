<?php

namespace rollun\test\Unit\App\Handler;

use App\Handler\HomePageHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class HomePageHandlerTest extends TestCase
{
    public function testSuccess()
    {
        $object = new HomePageHandler();

        /** @var ServerRequestInterface $requestMock */
        $requestMock = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $expectedResponse = new HtmlResponse('Home page!');

        $this->assertEquals(
            $expectedResponse->getBody()->getContents(),
            $object->handle($requestMock)->getBody()->getContents()
        );
    }
}