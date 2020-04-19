<?php
declare(strict_types=1);

namespace BuildkiteApi\Tests\Integration\Api\Rest;

use GuzzleHttp\Exception\ClientException;

final class BuildTest extends AbstractTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testListAll(): void
    {
        $list = $this->api->build()->listAll();
        $this->assertIsArray($list);
    }

    public function testGetByOrganization(): void
    {
        $list = $this->api->build()->getByOrganization($this->organization);
        $this->assertIsArray($list);
    }

    public function testGetByPipeline(): void
    {
        $repository = getenv('GITHUB_REPOSITORY');
        $pipelineApi = $this->api->pipeline();
        $pipelineSlug = $this->slugify(
            $this->prefix.'-ci-test-pipeline-'.getenv('GITHUB_REF')
        );

        try {
            $pipelineApi->get($this->organization, $pipelineSlug);
            $pipelineApi->delete($this->organization, $pipelineSlug);
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $this->assertEquals(404, $statusCode);
        }

        $pipeline = $pipelineApi->create(
            $this->organization,
            [
                'name' => $pipelineSlug,
                'repository' => 'git@github.com:'.$repository.'.git',
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
            $pipelineSlug = $pipeline['slug'];

            $list = $this->api->build()->getByPipeline($this->organization, $pipelineSlug);
            $this->assertIsArray($list);

            $buildSettings = [
                'commit' => 'HEAD',
                'branch' => 'master',
                'message' => 'Testing all the things :rocket:',
            ];

            $build = $this->api->build()->create(
                $this->organization,
                $pipelineSlug,
                $buildSettings
            );

            $this->assertIsArray($build);
            $this->assertArrayHasKey('number', $build);

            $buildNumber = $build['number'];

            $this->api->build()->get($this->organization, $pipelineSlug, $buildNumber);
            $this->api->build()->cancel($this->organization, $pipelineSlug, $buildNumber);
            $this->api->build()->rebuild($this->organization, $pipelineSlug, $buildNumber);
        } finally {
            $pipelineApi->delete($this->organization, $pipelineSlug);
        }
    }
}
