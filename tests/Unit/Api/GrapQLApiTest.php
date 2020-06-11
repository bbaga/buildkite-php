<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Unit\Api;

use bbaga\BuildkiteApi\Api\GraphQLApi;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class GrapQLApiTest extends TestCase
{
    public function testPost(): void
    {
        $testCase = $this;
        $token = 'my-token';

        $client = $this->prophesize(ClientInterface::class);

        $client->sendRequest(
            Argument::type(RequestInterface::class)
        )->will(function (array $args) use ($testCase, $token): ResponseInterface {
            /** @var RequestInterface $request */
            $request = $args[0];
            $authHeader = $request->getHeader('Authorization');
            $body = (string)$request->getBody();

            $testCase->assertEquals($authHeader[0], sprintf('Bearer %s', $token), 'Auth header set');
            $testCase->assertEquals('{"query":"{viewer { user { name } }}","variables":"{}"}', $body);

            $response = $testCase->prophesize(ResponseInterface::class);
            $response->withStatus()->willReturn(200);

            return $response->reveal();
        });

        $api = new GraphQLApi($client->reveal(), $token);
        $api->post('{viewer { user { name } }}', '{}');
    }
}
