<?php
declare(strict_types=1);

namespace BuildkiteApi\Tests\Integration\Api\Rest;

use GuzzleHttp\Exception\ClientException;

final class PipelineTest extends AbstractTestCase
{
    private $pipelineName;

    public function setUp(): void
    {
        parent::setUp();

        $pipelineName = $this->prefix.'-ci-test-pipeline-'.getenv('GITHUB_REF');
        $this->pipelineName = $this->slugify($pipelineName);
    }

    public function testInSequence(): void
    {
        $repository = getenv('GITHUB_REPOSITORY');
        $pipelineApi = $this->api->pipeline();

        try {
            $pipelineApi->get($this->organization, $this->pipelineName);
            $pipelineApi->delete($this->organization, $this->pipelineName);
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $this->assertEquals(404, $statusCode);
        }

        $pipelineApi->create(
            $this->organization,
            [
                'name' => $this->pipelineName,
                'repository' => 'git@github.com:' . $repository . '.git',
                'steps' => [
                    [
                        'type' => 'script',
                        'name' => 'Build :package:',
                        'command' => 'script/release.sh',
                    ],
                ],
            ]
        );

        try {
            $pipelineApi->update(
                $this->organization,
                $this->pipelineName,
                [
                    'steps' => [
                        [
                            'type' => 'script',
                            'name' => 'Test :package:',
                            'command' => 'script/Test.sh',
                        ],
                        [
                            'type' => 'waiter',
                        ],
                        [
                            'type' => 'script',
                            'name' => 'Build :package:',
                            'command' => 'script/release.sh',
                        ],
                    ],
                ]
            );

            $pipelineApi->list($this->organization);

            $pipelinesData = $pipelineApi->get($this->organization, $this->pipelineName);
        } finally {
            $pipelineApi->delete($this->organization, $this->pipelineName);
        }

        $this->assertIsArray($pipelinesData);
        $this->assertArrayHasKey('steps', $pipelinesData);
        $this->assertCount(3, $pipelinesData['steps']);

        try {
            $pipelineApi->get($this->organization, $this->pipelineName);
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(404, $statusCode);
    }
}
