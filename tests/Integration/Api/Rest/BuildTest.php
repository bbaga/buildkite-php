<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Integration\Api\Rest;

use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

final class BuildTest extends AbstractTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @psalm-suppress RedundantCondition
     */
    public function testListAll(): void
    {
        $list = $this->api->build()->listAll();
        $this->assertIsArray($list);
    }

    /**
     * @psalm-suppress RedundantCondition
     */
    public function testGetByOrganization(): void
    {
        $list = $this->api->build()->getByOrganization($this->organizationSlug);
        $this->assertIsArray($list);
    }

    /**
     * @psalm-suppress RedundantCondition
     */
    public function testGetByPipeline(): void
    {
        $repository = (string) getenv('GITHUB_REPOSITORY');
        $pipelineApi = $this->api->pipeline();
        $pipelineSlug = $this->slugify(
            $this->prefix . '-ci-test-pipeline-' . (string) getenv('GITHUB_REF')
        );

        try {
            $pipelineApi->get($this->organizationSlug, $pipelineSlug);
            $pipelineApi->delete($this->organizationSlug, $pipelineSlug);
        } catch (ClientException $e) {
            $response = $e->getResponse();

            if (!$response instanceof ResponseInterface) {
                throw $e;
            }

            $this->assertEquals(404, $response->getStatusCode());
        }

        $pipeline = $pipelineApi->create(
            $this->organizationSlug,
            [
                'name' => $pipelineSlug,
                'repository' => 'git@github.com:' . $repository . '.git',
                'steps' => [
                    [
                        'type' => 'script',
                        'name' => 'Build :package:',
                        'command' => 'script/release.sh',
                    ],
                ]
            ]
        );

        try {
            /** @var string $pipelineSlug */
            $pipelineSlug = $pipeline['slug'];

            $list = $this->api->build()->getByPipeline($this->organizationSlug, $pipelineSlug);
            $this->assertIsArray($list);

            $buildSettings = [
                'commit' => 'HEAD',
                'branch' => 'master',
                'message' => 'Testing all the things :rocket:',
            ];

            $build = $this->api->build()->create(
                $this->organizationSlug,
                $pipelineSlug,
                $buildSettings
            );

            $this->assertIsArray($build);
            $this->assertArrayHasKey('number', $build);

            /** @var int $buildNumber */
            $buildNumber = $build['number'];

            $this->api->build()->get($this->organizationSlug, $pipelineSlug, $buildNumber);
            $this->api->build()->cancel($this->organizationSlug, $pipelineSlug, $buildNumber);
            $this->api->build()->rebuild($this->organizationSlug, $pipelineSlug, $buildNumber);
        } finally {
            $pipelineApi->delete($this->organizationSlug, $pipelineSlug);
        }
    }
}
