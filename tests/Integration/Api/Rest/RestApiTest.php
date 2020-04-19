<?php
declare(strict_types=1);

namespace BuildkiteApi\Tests\Unit\Api\Rest;

use BuildkiteApi\Api\RestApi;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

final class Organization extends TestCase
{
    private $api;

    public function setUp(): void
    {
        parent::setUp();

        $token = getenv('BK_TEST_TOKEN');
        $this->api = new RestApi(new Client(), $token);
    }

    public function testList(): void
    {
        $organizations = $this->api->organization()->list();

        $this->assertCount(1, $organizations);
    }

    public function testGet(): void
    {
        $organizations = $this->api->organization()->get('bbaga-buildkite-php');

        $this->assertCount(1, $organizations);
    }
}
