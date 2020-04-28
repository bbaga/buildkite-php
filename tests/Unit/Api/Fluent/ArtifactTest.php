<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Unit\Api;

use bbaga\BuildkiteApi\Api\Rest\Fluent\Artifact;
use bbaga\BuildkiteApi\Api\Rest\Fluent\BuildInterface;
use bbaga\BuildkiteApi\Api\RestApiInterface;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ArtifactTest extends TestCase
{
    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function buildDataProvider(): array
    {
        $data = [
            'number' => 12,
            'id' => '123fr4wsdfrew',
            'jobs' => [['id' => 'some-job-id']],
        ];

        return [
            'Artifact data' => [$data],
        ];
    }

    public function testMissingId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The "id" must be a string identifying the artifact');
        $api = $this->prophesize(RestApiInterface::class)->reveal();
        $build = $this->prophesize(BuildInterface::class)->reveal();
        new Artifact($api, $build, []);
    }

    public function testInvalidId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The "id" must be a string identifying the artifact');
        $api = $this->prophesize(RestApiInterface::class)->reveal();
        $build = $this->prophesize(BuildInterface::class)->reveal();
        new Artifact($api, $build, ['id' => 1]);
    }

    public function testMissingJobId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The "job_id" must be a string identifying the job that produced the artifact');
        $api = $this->prophesize(RestApiInterface::class)->reveal();
        $build = $this->prophesize(BuildInterface::class)->reveal();
        new Artifact($api, $build, ['id' => 'something']);
    }

    public function testInvalidJobId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The "job_id" must be a string identifying the job that produced the artifact');
        $api = $this->prophesize(RestApiInterface::class)->reveal();
        $build = $this->prophesize(BuildInterface::class)->reveal();
        new Artifact($api, $build, ['id' => 'something', 'job_id' => []]);
    }
}
