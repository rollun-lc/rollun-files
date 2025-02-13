<?php

namespace rollun\test\Functional\App\Handler;

use App\Handler\HomePageHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;
use rollun\test\Functional\FunctionalTestCase;

class HomePageHandlerTest extends FunctionalTestCase
{
    public function testSuccess()
    {
        $handler = $this->getContainer()->get(HomePageHandler::class);

        /** @var ServerRequestInterface $requestMock */
        $requestMock = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $expectedResponse = new HtmlResponse('Home page!');

        $this->assertEquals(
            $expectedResponse->getBody()->getContents(),
            $handler->handle($requestMock)->getBody()->getContents()
        );
    }
}