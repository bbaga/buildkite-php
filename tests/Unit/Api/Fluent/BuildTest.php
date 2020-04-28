<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Unit\Api;

use bbaga\BuildkiteApi\Api\Rest\AnnotationInterface;
use bbaga\BuildkiteApi\Api\Rest\ArtifactInterface;
use bbaga\BuildkiteApi\Api\Rest\BuildInterface;
use bbaga\BuildkiteApi\Api\Rest\Fluent\Build;
use bbaga\BuildkiteApi\Api\Rest\Fluent\Organization;
use bbaga\BuildkiteApi\Api\Rest\Fluent\Pipeline;
use bbaga\BuildkiteApi\Api\Rest\Fluent\User;
use bbaga\BuildkiteApi\Api\RestApiInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class BuildTest extends TestCase
{
    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function buildDataProvider(): array
    {
        $data = [
            'number' => 12,
            'id' => '123fr4wsdfrew',
            'url' => 'url',
            'web_url' => 'web-url',
            'state' => 'blocked',
            'blocked' => true,
            'message' => 'Test Build',
            'commit' => 'a23df4',
            'branch' => 'master',
            'tag' => 'tag?',
            'env' => ['MY_ENV' => 'foo'],
            'source' => 'ui',
            'created_at' => 'created_at_ts',
            'scheduled_at' => 'scheduled_at_ts',
            'started_at' => 'started_at_ts',
            'finished_at' => 'finished_at_ts',
            'meta_data' => [],
            'pull_request' => [],
            'creator' => ['name' => 'John Doe'],
            'pipeline' => ['slug' => 'my-pipeline'],
            'jobs' => [['id' => 'some-job-id']],
        ];

        return [
            'Build data' => [$data],
        ];
    }

    /**
     * @param array<string, mixed> $buildData
     * @dataProvider buildDataProvider
     */
    public function testFetch(array $buildData): void
    {
        $orgSlug = 'my-org';
        /** @var array $pipelineData */
        $pipelineData = $buildData['pipeline'];

        $restApi = $this->prophesize(RestApiInterface::class);
        $buildApi = $this->prophesize(BuildInterface::class);
        $buildApi->get(
            Argument::exact($orgSlug),
            Argument::exact($pipelineData['slug']),
            Argument::exact($buildData['number'])
        )->willReturn($buildData);
        $restApi->build()->willReturn($buildApi->reveal());
        $restApiMock = $restApi->reveal();

        $organization = new Organization($restApiMock, ['slug' => 'my-org']);

        $build = new Build(
            $restApiMock,
            $organization,
            $buildData
        );
        $build->fetch();

        $this->assertEquals($buildData['id'], $build->getId());
        $this->assertEquals($buildData['number'], $build->getNumber());
        $this->assertEquals($buildData['url'], $build->getUrl());
        $this->assertEquals($buildData['web_url'], $build->getWebUrl());
        $this->assertEquals($buildData['state'], $build->getState());
        $this->assertEquals($buildData['blocked'], $build->isBlocked());
        $this->assertEquals($buildData['message'], $build->getMessage());
        $this->assertEquals($buildData['commit'], $build->getCommit());
        $this->assertEquals($buildData['branch'], $build->getBranch());
        $this->assertEquals($buildData['tag'], $build->getTag());
        $this->assertEquals($buildData['env'], $build->getEnv());
        $this->assertEquals($buildData['source'], $build->getSource());
        $this->assertEquals($buildData['created_at'], $build->getCreatedAt());
        $this->assertEquals($buildData['scheduled_at'], $build->getScheduledAt());
        $this->assertEquals($buildData['started_at'], $build->getStartedAt());
        $this->assertEquals($buildData['finished_at'], $build->getFinishedAt());
        $this->assertEquals($buildData['meta_data'], $build->getMetaData());
        $this->assertEquals($buildData['pull_request'], $build->getPullRequest());
        $this->assertInstanceOf(User::class, $build->getCreator());
        $this->assertInstanceOf(Pipeline::class, $build->getPipeline());
        $this->assertCount(1, $build->getJobs());
        $this->assertEquals($pipelineData['slug'], $build->getPipelineSlug());
        $this->assertEquals($organization->getSlug(), $build->getOrganizationSlug());
    }

    /**
     * @param array<string, mixed> $buildData
     * @dataProvider buildDataProvider
     */
    public function testCancel(array $buildData): void
    {
        $orgSlug = 'my-org';
        /** @var array $pipelineData */
        $pipelineData = $buildData['pipeline'];

        $restApi = $this->prophesize(RestApiInterface::class);
        $buildApi = $this->prophesize(BuildInterface::class);
        $buildApi->cancel(
            Argument::exact($orgSlug),
            Argument::exact($pipelineData['slug']),
            Argument::exact($buildData['number'])
        )->willReturn($buildData);
        $restApi->build()->willReturn($buildApi->reveal());
        $restApiMock = $restApi->reveal();

        $organization = new Organization($restApiMock, ['slug' => 'my-org']);

        $build = new Build(
            $restApiMock,
            $organization,
            $buildData
        );
        $build->cancel();

        $this->assertEquals($buildData['url'], $build->getUrl());
    }

    /**
     * @param array<string, mixed> $buildData
     * @dataProvider buildDataProvider
     */
    public function testRebuild(array $buildData): void
    {
        $orgSlug = 'my-org';
        /** @var array $pipelineData */
        $pipelineData = $buildData['pipeline'];

        $restApi = $this->prophesize(RestApiInterface::class);
        $buildApi = $this->prophesize(BuildInterface::class);
        $buildApi->rebuild(
            Argument::exact($orgSlug),
            Argument::exact($pipelineData['slug']),
            Argument::exact($buildData['number'])
        )->willReturn($buildData);
        $restApi->build()->willReturn($buildApi->reveal());
        $restApiMock = $restApi->reveal();

        $organization = new Organization($restApiMock, ['slug' => 'my-org']);

        $build = new Build(
            $restApiMock,
            $organization,
            $buildData
        );
        $build->rebuild();

        $this->assertEquals($buildData['url'], $build->getUrl());
    }

    public function testGetAnnotations(): void
    {
        $orgSlug = 'my-org';
        /** @var array $pipelineData */
        $pipelineData = ['slug' => 'my-pipeline'];
        $buildNumber = 31;

        $expectedResult = [[]];

        $restApi = $this->prophesize(RestApiInterface::class);
        $annotationApi = $this->prophesize(AnnotationInterface::class);
        $annotationApi->list(
            Argument::exact($orgSlug),
            Argument::exact($pipelineData['slug']),
            Argument::exact($buildNumber),
            Argument::type('array')
        )->willReturn($expectedResult);
        $restApi->annotation()->willReturn($annotationApi->reveal());
        $restApiMock = $restApi->reveal();

        $organization = new Organization($restApiMock, ['slug' => 'my-org']);

        $build = new Build(
            $restApiMock,
            $organization,
            ['number' => $buildNumber, 'pipeline' => $pipelineData]
        );

        $results = $build->getAnnotations();
        $this->assertCount(1, $results);
    }

    public function testGetArtifacts(): void
    {
        $orgSlug = 'my-org';
        /** @var array $pipelineData */
        $pipelineData = ['slug' => 'my-pipeline'];
        $buildNumber = 15;

        $expectedResult = [
            [
                'id' => 'some-id',
                'job_id' => 'some-job-id',
            ]
        ];

        $restApi = $this->prophesize(RestApiInterface::class);
        $annotationApi = $this->prophesize(ArtifactInterface::class);
        $annotationApi->getByBuild(
            Argument::exact($orgSlug),
            Argument::exact($pipelineData['slug']),
            Argument::exact($buildNumber),
            Argument::type('array')
        )->willReturn($expectedResult);
        $restApi->artifact()->willReturn($annotationApi->reveal());
        $restApiMock = $restApi->reveal();

        $organization = new Organization($restApiMock, ['slug' => 'my-org']);

        $build = new Build(
            $restApiMock,
            $organization,
            ['number' => $buildNumber, 'pipeline' => $pipelineData]
        );

        $results = $build->getArtifacts();
        $this->assertCount(1, $results);
    }
}
