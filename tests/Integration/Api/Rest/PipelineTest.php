<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Integration\Api\Rest;

use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class PipelineTest extends AbstractTestCase
{
    /**
     * @var string
     */
    private $pipelineName;

    public function setUp(): void
    {
        parent::setUp();

        $pipelineName = $this->prefix . '-ci-test-pipeline-' . (string) getenv('GITHUB_REF');
        $this->pipelineName = $this->slugify($pipelineName);
    }

    /**
     * @psalm-suppress RedundantCondition
     */
    public function testInSequence(): void
    {
        $repository = (string) getenv('GITHUB_REPOSITORY');
        $pipelineApi = $this->api->pipeline();

        try {
            $pipelineApi->get($this->organizationSlug, $this->pipelineName);
            $pipelineApi->delete($this->organizationSlug, $this->pipelineName);
        } catch (ClientException $e) {
            $response = $e->getResponse();

            if (!$response instanceof ResponseInterface) {
                throw $e;
            }

            $this->assertEquals(404, $response->getStatusCode());
        }

        $pipelineApi->create(
            $this->organizationSlug,
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
                $this->organizationSlug,
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

            $pipelineApi->list($this->organizationSlug);

            $pipelinesData = $pipelineApi->get($this->organizationSlug, $this->pipelineName);
        } finally {
            $pipelineApi->delete($this->organizationSlug, $this->pipelineName);
        }

        $this->assertIsArray($pipelinesData);
        $this->assertArrayHasKey('steps', $pipelinesData);
        /** @var array $steps */
        $steps = $pipelinesData['steps'] ?? [];
        $this->assertCount(3, $steps);

        try {
            $pipelineApi->get($this->organizationSlug, $this->pipelineName);
        } catch (ClientException $e) {
            $response = $e->getResponse();

            if (!$response instanceof ResponseInterface) {
                throw $e;
            }

            $statusCode = $response->getStatusCode();
        }

        $this->assertEquals(404, $statusCode ?? 0);
    }
}
