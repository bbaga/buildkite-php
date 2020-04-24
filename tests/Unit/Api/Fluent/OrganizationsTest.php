<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Unit\Api;

use bbaga\BuildkiteApi\Api\Rest\Fluent\Organization;
use bbaga\BuildkiteApi\Api\Rest\Fluent\Organizations;
use bbaga\BuildkiteApi\Api\RestApiInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class OrganizationsTest extends TestCase
{
    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function orgDataProvider(): array
    {
        $orgData = [
            'id' => 'abcd-123',
            'name' => 'My Org',
            'slug' => 'my-org',
            'url' => 'my-org',
            'web_url' => 'http://bk.com/my-org',
            'pipelines_url' => 'http://bk.com/my-org',
            'agents_url' => 'https://buildkite.com/organizations/my-org/agents',
            'emojis_url' => 'http://bk.com/my-org/emojis',
            'created_at' => '2020-04-23T20:47:13.883Z',
        ];

        return [
            'Organization data' => [$orgData],
        ];
    }

    /**
     * @dataProvider orgDataProvider
     */
    public function testGet(array $orgData): void
    {
        $restApi = $this->prophesize(RestApiInterface::class);

        $response = [$orgData];

        $restApi->get(Argument::any())->willReturn($this->prophesize(ResponseInterface::class));
        $restApi->getResponseBody(Argument::any())->willReturn($response);

        $organizations = new Organizations($restApi->reveal());
        $list = $organizations->get();

        $this->assertCount(1, $list);

        /** @var Organization $org */
        $org = array_pop($list);
        $this->assertEquals($orgData['id'], $org->getId());
        $this->assertEquals($orgData['name'], $org->getName());
        $this->assertEquals($orgData['slug'], $org->getSlug());
        $this->assertEquals($orgData['url'], $org->getUrl());
        $this->assertEquals($orgData['web_url'], $org->getWebUrl());
        $this->assertEquals($orgData['pipelines_url'], $org->getPipelinesUrl());
        $this->assertEquals($orgData['agents_url'], $org->getAgentsUrl());
        $this->assertEquals($orgData['emojis_url'], $org->getEmojisUrl());
        $this->assertEquals($orgData['created_at'], $org->getCreatedAt());
    }
}
