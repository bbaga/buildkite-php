<?php
declare(strict_types=1);

namespace BuildkiteApi\Tests\Integration\Api\Rest;

use BuildkiteApi\Api\RestApi;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @var RestApi
     */
    protected $api;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $organization;

    public function setUp(): void
    {
        parent::setUp();

        $token = getenv('BK_TEST_TOKEN');
        $this->prefix = getenv('BK_TEST_PREFIX');
        $this->organization = getenv('BK_TEST_ORG');
        $this->api = new RestApi(new Client(), $token);
    }

    protected function slugify(string $name): string
    {
        return preg_replace('/[^a-z|0-9]+/i', '-', $name);
    }
}
