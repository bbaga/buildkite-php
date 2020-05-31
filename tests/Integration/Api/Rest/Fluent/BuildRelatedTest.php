<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Tests\Integration\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\Rest\Fluent\Annotation;
use bbaga\BuildkiteApi\Api\Rest\Fluent\Artifact;
use bbaga\BuildkiteApi\Api\Rest\Fluent\Job;
use bbaga\BuildkiteApi\Api\Rest\Fluent\Organization;
use bbaga\BuildkiteApi\Api\Rest\Fluent\Pipeline;
use bbaga\BuildkiteApi\Tests\Integration\Api\Rest\AbstractTestCase;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use function in_array;

final class BuildRelatedTest extends AbstractTestCase
{
    /**
     * @var string
     */
    private $pipelineSlug;

    /**
     * @var Organization
     */
    private $organization;

    /**
     * @var Pipeline
     */
    private $pipeline;

    public function setUp(): void
    {
        parent::setUp();

        $this->pipelineSlug = $this->slugify(
            $this->prefix . '-ci-test-pipeline-' . (string) getenv('GITHUB_REF')
        );

        $this->organization = new Organization($this->api, ['slug' => $this->organizationSlug]);
        $this->pipeline = new Pipeline($this->api, $this->organization, ['slug' => $this->pipelineSlug]);

        $this->deletePipeline();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deletePipeline();
    }

    /**
     * @psalm-suppress RedundantCondition
     */
    public function testBuildRelatedFunctions(): void
    {
        $repository = (string) getenv('GITHUB_REPOSITORY');

        $this->pipeline = $this->organization->createPipeline(
            [
                'name' => $this->pipelineSlug,
                'repository' => 'git@github.com:' . $repository . '.git',
                'steps' => [
                    [
                        'type' => 'script',
                        'name' => 'upload artifact',
                        'command' => 'echo "Hello" > artifact.txt \
                            && buildkite-agent artifact upload artifact.txt \
                            && cat artifact.txt | buildkite-agent annotate --style "success" --context "junit"',
                    ],
                    [
                        'type' => 'manual',
                        'name' => 'Needs to be unblocked',
                        'command' => 'echo "Unblocked"',
                    ],
                ]
            ]
        );

        $list = $this->pipeline->getBuilds();
        $this->assertIsArray($list);
        $this->assertCount(0, $list);

        $buildSettings = [
            'commit' => 'HEAD',
            'branch' => 'master',
            'message' => 'Testing all the things :rocket:',
        ];

        $build = $this->pipeline->createBuild($buildSettings);
        $this->assertNotEmpty($build->getNumber());

        $build->fetch();
        $build->cancel();
        $rebuild = $build->rebuild();

        $timeoutCounter = 0;
        do {
            if (++$timeoutCounter > 120) {
                throw new \RuntimeException('Build did not finish in time');
            }

            sleep(1);
            $rebuild->fetch();
        } while (!in_array($rebuild->getState(), ['passed', 'failed', 'blocked', 'canceled']));

        /**
         * Testing Job related methods
         */
        $jobs = $rebuild->getJobs();
        $this->assertCount(2, $jobs);
        /** @var Job $scriptJob */
        $scriptJob = $jobs[0];
        $this->assertCount(1, $scriptJob->getArtifacts());

        $scriptJob->getId();
        $this->assertEquals('script', $scriptJob->getType());
        $this->assertEquals('upload artifact', $scriptJob->getName());
        $scriptJob->getStepKey();
        $scriptJob->getAgentQueryRules();
        $scriptJob->getBuildUrl();
        $scriptJob->getWebUrl();
        $scriptJob->getLogUrl();
        $scriptJob->getRawLogUrl();
        $scriptJob->getArtifactsUrl();
        $scriptJob->getCommand();
        $scriptJob->isSoftFailed();
        $scriptJob->getExitStatus();
        $scriptJob->getArtifactPaths();
        $scriptJob->getAgent();
        $scriptJob->getCreatedAt();
        $scriptJob->getScheduledAt();
        $scriptJob->getRunnableAt();
        $scriptJob->getStartedAt();
        $scriptJob->getFinishedAt();
        $scriptJob->isRetried();
        $scriptJob->getRetriedInJobId();
        $scriptJob->getRetriesCount();
        $scriptJob->getParallelGroupIndex();
        $scriptJob->getParallelGroupTotal();
        $scriptJob->getUnblockedBy();
        $scriptJob->getUnblockedAt();
        $scriptJob->isUnblockable();
        $scriptJob->getUnblockUrl();

        /** @var array<string, string> $logOutput */
        $logOutput = $scriptJob->getLogOutput();
        $this->assertArrayHasKey('content', $logOutput);
        $this->assertStringContainsString('buildkite-agent artifact upload artifact.txt', $logOutput['content']);
        $scriptJob->deleteLogOutput();
        $logOutput = $scriptJob->getLogOutput();
        $this->assertArrayHasKey('content', $logOutput);
        $this->assertEmpty($logOutput['content']);

        /** @var Job $blockedJob */
        $blockedJob = $jobs[1];
        $this->assertEquals('blocked', $blockedJob->getState());
        $blockedJob->unblock();
        $this->assertNotEquals('blocked', $blockedJob->getState());

        /**
         * Testing Artifacts related methods
         */
        $artifacts = $rebuild->getArtifacts();
        $this->assertCount(1, $artifacts);
        /** @var Artifact $artifact */
        $artifact = $artifacts[0];

        $this->assertStringStartsWith('http', $artifact->getDownloadUrl());

        $artifact->delete();
        $this->assertEquals('deleted', $artifact->getState());

        $artifacts = $rebuild->getArtifacts();
        $this->assertCount(1, $artifacts);
        /** @var Artifact $artifact */
        $artifact = $artifacts[0];
        $this->assertEquals('deleted', $artifact->getState());

        /**
         * Testing Annotations related methods
         */
        $annotations = $rebuild->getAnnotations();
        $this->assertCount(1, $annotations);
        /** @var Annotation $annotation */
        $annotation = $annotations[0];
        $this->assertStringContainsString('Hello', $annotation->getBodyHtml());
        $this->assertEquals('junit', $annotation->getContext());
        $this->assertEquals('success', $annotation->getStyle());
    }

    private function deletePipeline(): void
    {
        try {
            $this->pipeline->delete();
        } catch (ClientException $e) {
            $response = $e->getResponse();

            if (!$response instanceof ResponseInterface) {
                throw $e;
            }

            $this->assertEquals(404, $response->getStatusCode());
        }
    }
}
