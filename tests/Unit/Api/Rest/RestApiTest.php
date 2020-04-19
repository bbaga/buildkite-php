<?php
declare(strict_types=1);

namespace BuildkiteApi\Tests\Unit\Api\Rest;

use BuildkiteApi\Api\RestApi;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

final class RestApiTest extends TestCase
{
    public function testGetResponseBody(): void
    {
        $client = $this->prophesize(Client::class)->reveal();
        $api = new RestApi($client, 'token');

        $response = new Response(200, [], '{"foo": "bar"}');

        $this->assertEquals(['foo' => 'bar'], $api->getResponseBody($response));
    }

    public function testGet(): void
    {
        $testCase = $this;
        $token = 'my-token';

        $client = $this->prophesize(Client::class);
        $client->send(
            Argument::type(RequestInterface::class),
            Argument::type('array')
        )->will(function($args) use ($testCase, $token) {
            /** @var RequestInterface $request */
            $request = $args[0];
            /** @var array $options */
            $options = $args[1];
            $authHeader = $request->getHeader('Authorization');

            $testCase->assertEquals($authHeader[0], sprintf('Bearer %s', $token), 'Auth header set');
            $testCase->assertArrayHasKey('test', $options, 'Option key set');
            $testCase->assertEquals('dummy', $options['test'], 'Option value set');

            return new Response(200);
        });

        $api = new RestApi($client->reveal(), $token);
        $api->get('sdfsd', ['test' => 'dummy']);
    }
}
