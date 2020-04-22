<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Integration\Api\Rest;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class OrganizationTest extends AbstractTestCase
{
    public function testList(): void
    {
        $organizations = $this->api->organization()->list();

        $this->assertCount(1, $organizations);
    }

    public function testGet(): void
    {
        $organizations = $this->api->organization()->get($this->organization);

        $this->assertNotEmpty($organizations);
    }
}
