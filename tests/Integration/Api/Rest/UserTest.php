<?php
declare(strict_types=1);

namespace BuildkiteApi\Tests\Integration\Api\Rest;

final class UserTest extends AbstractTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testWhoAmI(): void
    {
        $iam = $this->api->user()->whoami();
        $this->assertIsArray($iam);
        $this->assertArrayHasKey('id', $iam);
        $this->assertArrayHasKey('name', $iam);
        $this->assertArrayHasKey('email', $iam);
        $this->assertArrayHasKey('avatar_url', $iam);
        $this->assertArrayHasKey('created_at', $iam);
    }
}
