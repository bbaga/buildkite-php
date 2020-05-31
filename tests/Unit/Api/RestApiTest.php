<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Unit\Api;

use bbaga\BuildkiteApi\Api\HttpClientInterface;
use bbaga\BuildkiteApi\Api\RestApi;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class RestApiTest extends TestCase
{
    public function testGetResponseBody(): void
    {
        $client = $this->prophesize(HttpClientInterface::class)->reveal();
        $api = new RestApi($client, 'token');
        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn('{"foo": "bar"}');

        $this->assertEquals(['foo' => 'bar'], $api->getResponseBody($response->reveal()));
    }

    public function testGet(): void
    {
        $testCase = $this;
        $token = 'my-token';

        $client = $this->prophesize(HttpClientInterface::class);
        $client->createRequest('GET', RestApi::BASE_URI . 'some/uri')->willReturn(
            new Request('GET', RestApi::BASE_URI . 'some/uri')
        );

        $client->sendRequest(
            Argument::type(RequestInterface::class)
        )->will(function (array $args) use ($testCase, $token): ResponseInterface {
            /** @var RequestInterface $request */
            $request = $args[0];
            $authHeader = $request->getHeader('Authorization');

            $testCase->assertEquals($authHeader[0], sprintf('Bearer %s', $token), 'Auth header set');

            $response = $testCase->prophesize(ResponseInterface::class);
            $response->withStatus()->willReturn(200);

            return $response->reveal();
        });

        $api = new RestApi($client->reveal(), $token);
        $api->get('some/uri', ['test' => 'dummy']);
    }
}
