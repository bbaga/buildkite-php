<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Unit\Api;

use bbaga\BuildkiteApi\Api\Rest\BuildInterface;
use bbaga\BuildkiteApi\Api\Rest\Fluent\Organization;
use bbaga\BuildkiteApi\Api\Rest\Fluent\Pipeline;
use bbaga\BuildkiteApi\Api\Rest\PipelineInterface;
use bbaga\BuildkiteApi\Api\RestApiInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class PipelineTest extends TestCase
{
    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function pipelineDataProvider(): array
    {
        $pipelineData = [
            'id' => 'abcd-123',
            'url' => 'some-url',
            'web_url' => 'some-web-url',
            'name' => 'My Pipeline',
            'description' => 'this is my test pipeline',
            'slug' => 'my-pipeline',
            'repository' => 'git@github.com:some/repo.git',
            'branch_configuration' => '!master',
            'default_branch' => 'master',
            'skip_queued_branch_builds' => true,
            'skip_queued_branch_builds_filter' => 'feature*',
            'cancel_running_branch_builds' => true,
            'cancel_running_branch_builds_filter' => 'feature*',
            'provider' => ['provider-key' => 'value'],
            'builds_url' => 'builds-url',
            'badge_url' => 'badge-url',
            'created_at' => 'some timestamp',
            'env' => ['MY_ENV' => 'foo'],
            'scheduled_builds_count' => 12,
            'running_builds_count' => 2,
            'scheduled_jobs_count' => 63,
            'running_jobs_count' => 24,
            'waiting_jobs_count' => 40,
            'visibility' => 'public',
            'steps' => [['command' => 'echo "hello world"']],
            'configuration' => 'steps: []',
        ];

        return [
            'Pipeline data' => [$pipelineData],
        ];
    }

    /**
     * @param array<string, mixed> $pipelineData
     * @dataProvider pipelineDataProvider
     */
    public function testFetch(array $pipelineData): void
    {
        $restApi = $this->prophesize(RestApiInterface::class);

        $pipelineApi = $this->prophesize(PipelineInterface::class);
        $pipelineApi->get(Argument::any(), Argument::any())->willReturn($pipelineData);
        $restApi->pipeline()->willReturn($pipelineApi->reveal());
        $restApiMock = $restApi->reveal();

        $organization = new Organization($restApiMock, ['slug' => 'my-org']);

        $pipeline = new Pipeline(
            $restApiMock,
            $organization,
            ['slug' => $pipelineData['slug']]
        );
        $pipeline->fetch();

        $this->assertEquals($pipelineData['id'], $pipeline->getId());
        $this->assertEquals($pipelineData['url'], $pipeline->getUrl());
        $this->assertEquals($pipelineData['web_url'], $pipeline->getWebUrl());
        $this->assertEquals($pipelineData['name'], $pipeline->getName());
        $this->assertEquals($pipelineData['description'], $pipeline->getDescription());
        $this->assertEquals($pipelineData['slug'], $pipeline->getSlug());
        $this->assertEquals($pipelineData['repository'], $pipeline->getRepository());
        $this->assertEquals($pipelineData['branch_configuration'], $pipeline->getBranchConfiguration());
        $this->assertEquals($pipelineData['default_branch'], $pipeline->getDefaultBranch());
        $this->assertEquals($pipelineData['skip_queued_branch_builds'], $pipeline->getSkipQueuedBranchBuilds());
        $this->assertEquals($pipelineData['skip_queued_branch_builds_filter'], $pipeline->getSkipQueuedBranchBuildsFilter());
        $this->assertEquals($pipelineData['cancel_running_branch_builds'], $pipeline->getCancelRunningBranchBuilds());
        $this->assertEquals($pipelineData['cancel_running_branch_builds_filter'], $pipeline->getCancelRunningBranchBuildsFilter());
        $this->assertEquals($pipelineData['provider'], $pipeline->getProvider());
        $this->assertEquals($pipelineData['builds_url'], $pipeline->getBuildsUrl());
        $this->assertEquals($pipelineData['badge_url'], $pipeline->getBadgeUrl());
        $this->assertEquals($pipelineData['created_at'], $pipeline->getCreatedAt());
        $this->assertEquals($pipelineData['env'], $pipeline->getEnv());
        $this->assertEquals($pipelineData['scheduled_builds_count'], $pipeline->getScheduledBuildsCount());
        $this->assertEquals($pipelineData['running_builds_count'], $pipeline->getRunningBuildsCount());
        $this->assertEquals($pipelineData['scheduled_jobs_count'], $pipeline->getScheduledJobsCount());
        $this->assertEquals($pipelineData['running_jobs_count'], $pipeline->getRunningJobsCount());
        $this->assertEquals($pipelineData['waiting_jobs_count'], $pipeline->getWaitingJobsCount());
        $this->assertEquals($pipelineData['visibility'], $pipeline->getVisibility());
        $this->assertEquals($pipelineData['steps'], $pipeline->getSteps());
        $this->assertEquals($pipelineData['configuration'], $pipeline->getConfiguration());
        $this->assertSame($organization, $pipeline->getOrganization());
    }

    public function testGetBuilds(): void
    {
        $orgSlug = 'my-org';
        $pipelineSlug = 'my-pipeline';

        $restApi = $this->prophesize(RestApiInterface::class);

        $buildApi = $this->prophesize(BuildInterface::class);
        $buildApi->getByPipeline(
            Argument::exact($orgSlug),
            Argument::exact($pipelineSlug),
            Argument::any()
        )->willReturn([['number' => 1]]);

        $restApi->build()->willReturn($buildApi->reveal());

        $restApiMock = $restApi->reveal();
        $pipeline = new Pipeline(
            $restApiMock,
            new Organization($restApiMock, ['slug' => $orgSlug]),
            ['slug' => $pipelineSlug]
        );
        $builds = $pipeline->getBuilds();
        $this->assertCount(1, $builds);
        $this->assertSame($pipeline, $builds[0]->getPipeline());
    }

    public function testCreateBuild(): void
    {
        $orgSlug = 'my-org';
        $pipelineSlug = 'my-pipeline';

        $restApi = $this->prophesize(RestApiInterface::class);
        $buildApi = $this->prophesize(BuildInterface::class);
        $restApi->build()->willReturn($buildApi->reveal());
        $restApiMock = $restApi->reveal();

        $pipeline = new Pipeline(
            $restApiMock,
            new Organization($restApiMock, ['slug' => $orgSlug]),
            ['slug' => $pipelineSlug]
        );

        $buildApi->create(
            Argument::exact($orgSlug),
            Argument::exact($pipelineSlug),
            Argument::any()
        )->willReturn(['number' => 1, 'pipeline' => $pipeline]);

        $build = $pipeline->createBuild([]);
        $this->assertSame($pipeline, $build->getPipeline());
    }

    public function testUpdate(): void
    {
        $pipelineData = ['name' => 'My Pipeline'];
        $pipelineSlug = 'my-pipeline';
        $organization = new Organization(
            $this->prophesize(RestApiInterface::class)->reveal(),
            ['slug' => 'my-org']
        );

        $restApi = $this->prophesize(RestApiInterface::class);
        $pipelineApi = $this->prophesize(PipelineInterface::class);
        $pipelineApi->update(
            Argument::exact($organization->getSlug()),
            Argument::exact($pipelineSlug),
            Argument::exact($pipelineData)
        )->willReturn([])
            ->shouldBeCalled();

        $restApi->pipeline()->willReturn($pipelineApi->reveal());
        $restApiMock = $restApi->reveal();

        $pipeline = new Pipeline(
            $restApiMock,
            $organization,
            ['slug' => $pipelineSlug]
        );
        $pipeline->update($pipelineData);
    }

    public function testDelete(): void
    {
        $pipelineSlug = 'my-pipeline';
        $organization = new Organization(
            $this->prophesize(RestApiInterface::class)->reveal(),
            ['slug' => 'my-org']
        );

        $restApi = $this->prophesize(RestApiInterface::class);
        $pipelineApi = $this->prophesize(PipelineInterface::class);
        $pipelineApi->delete(
            Argument::exact($organization->getSlug()),
            Argument::exact($pipelineSlug)
        )->shouldBeCalled();

        $restApi->pipeline()->willReturn($pipelineApi->reveal());
        $restApiMock = $restApi->reveal();

        $pipeline = new Pipeline(
            $restApiMock,
            $organization,
            ['slug' => $pipelineSlug]
        );
        $pipeline->delete();
    }
}
