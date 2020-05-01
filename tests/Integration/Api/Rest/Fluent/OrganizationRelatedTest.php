<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Integration\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\Rest\Fluent\Organization;
use bbaga\BuildkiteApi\Api\Rest\Fluent\Pipeline;
use bbaga\BuildkiteApi\Tests\Integration\Api\Rest\AbstractTestCase;

final class OrganizationRelatedTest extends AbstractTestCase
{
    /**
     * @var Organization
     */
    private $organization;

    public function setUp(): void
    {
        parent::setUp();


        $this->organization = new Organization($this->api, ['slug' => $this->organizationSlug]);
    }

    public function testGetAgents(): void
    {
        $agents = $this->organization->getAgents();
        $this->assertCount(1, $agents);
    }

    public function testGetBuilds(): void
    {
        $builds = $this->organization->getBuilds();
        $this->assertNotCount(0, $builds);
    }

    public function testGetEmojis(): void
    {
        $builds = $this->organization->getEmojis();
        $this->assertNotCount(0, $builds);
    }

    /**
     * @psalm-suppress RedundantCondition
     */
    public function testGetPipelines(): void
    {
        $pipelines = $this->organization->getPipelines();
        $this->assertIsArray($pipelines);
    }
}
