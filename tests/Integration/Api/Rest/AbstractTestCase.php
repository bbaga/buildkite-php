<?php
declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Integration\Api\Rest;

use bbaga\BuildkiteApi\Api\GuzzleClient;
use bbaga\BuildkiteApi\Api\RestApi;
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

        $token = (string) getenv('BK_TEST_TOKEN');
        $this->prefix = (string) getenv('BK_TEST_PREFIX');
        $this->organization = (string) getenv('BK_TEST_ORG');
        $this->api = new RestApi(new GuzzleClient(), $token);
    }

    protected function slugify(string $name): string
    {
        return preg_replace('/[^a-z|0-9]+/i', '-', $name);
    }
}
