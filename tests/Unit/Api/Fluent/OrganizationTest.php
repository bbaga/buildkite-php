<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Unit\Api;

use bbaga\BuildkiteApi\Api\Rest\AgentInterface;
use bbaga\BuildkiteApi\Api\Rest\EmojiInterface;
use bbaga\BuildkiteApi\Api\Rest\Fluent\Organization;
use bbaga\BuildkiteApi\Api\Rest\OrganizationInterface;
use bbaga\BuildkiteApi\Api\Rest\PipelineInterface;
use bbaga\BuildkiteApi\Api\RestApiInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class OrganizationTest extends TestCase
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
    public function testFetch(array $orgData): void
    {
        $restApi = $this->prophesize(RestApiInterface::class);

        $organizationApi = $this->prophesize(OrganizationInterface::class);
        $organizationApi->get(Argument::any())->willReturn($orgData);
        $restApi->organization()->willReturn($organizationApi->reveal());

        $org = new Organization($restApi->reveal(), ['slug' => $orgData['slug']]);
        $org->fetch();

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

    public function testGetPipelines(): void
    {
        $orgSlug = 'my-org';

        $restApi = $this->prophesize(RestApiInterface::class);

        $pipelineApi = $this->prophesize(PipelineInterface::class);
        $pipelineApi->list(Argument::exact($orgSlug), Argument::any())->willReturn([[]]);
        $restApi->pipeline()->willReturn($pipelineApi->reveal());

        $org = new Organization($restApi->reveal(), ['slug' => $orgSlug]);
        $pipelines = $org->getPipelines();
        $this->assertCount(1, $pipelines);
    }

    public function testGetAgents(): void
    {
        $orgSlug = 'my-org';

        $restApi = $this->prophesize(RestApiInterface::class);

        $agentInterface = $this->prophesize(AgentInterface::class);
        $agentInterface->list(Argument::exact($orgSlug), Argument::any())->willReturn([['id' => 'agent-id-1234']]);
        $restApi->agent()->willReturn($agentInterface->reveal());

        $org = new Organization($restApi->reveal(), ['slug' => $orgSlug]);
        $agents = $org->getAgents();
        $this->assertCount(1, $agents);
    }

    public function testGetEmojis(): void
    {
        $orgSlug = 'my-org';

        $restApi = $this->prophesize(RestApiInterface::class);

        $emojiInterface = $this->prophesize(EmojiInterface::class);
        $emojiInterface->list(Argument::exact($orgSlug), Argument::any())->willReturn([[]]);
        $restApi->emoji()->willReturn($emojiInterface->reveal());

        $org = new Organization($restApi->reveal(), ['slug' => $orgSlug]);
        $agents = $org->getEmojis();
        $this->assertCount(1, $agents);
    }

    public function testMissingSlugException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $restApi = $this->prophesize(RestApiInterface::class);
        new Organization($restApi->reveal(), []);
    }
}
