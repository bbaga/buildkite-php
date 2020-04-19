<?php
declare(strict_types=1);

namespace BuildkiteApi\Api\Rest;

use BuildkiteApi\Api\RestApi;

final class Job
{
    /**
     * @var RestApi
     */
    private $api;

    public function __construct(RestApi $api)
    {
        $this->api = $api;
    }

    public function retry(
        string $organizationSlug,
        string $pipelineSlug,
        int $buildNumber,
        string $jobId
    ): array {
        $uri = sprintf(
            'organizations/%s/pipelines/%s/builds/%d/jobs/%s/retry',
            $organizationSlug,
            $pipelineSlug,
            $buildNumber,
            $jobId
        );
        $response = $this->api->put($uri);

        return $this->api->getResponseBody($response);
    }

    public function unlock(
        string $organizationSlug,
        string $pipelineSlug,
        int $buildNumber,
        string $jobId
    ): array {
        $uri = sprintf(
            'organizations/%s/pipelines/%s/builds/%d/jobs/%s/unlock',
            $organizationSlug,
            $pipelineSlug,
            $buildNumber,
            $jobId
        );
        $response = $this->api->put($uri);

        return $this->api->getResponseBody($response);
    }

    public function getLogOutput(
        string $organizationSlug,
        string $pipelineSlug,
        int $buildNumber,
        string $jobId
    ): array {
        $uri = sprintf(
            'organizations/%s/pipelines/%s/builds/%d/jobs/%s/log',
            $organizationSlug,
            $pipelineSlug,
            $buildNumber,
            $jobId
        );
        $response = $this->api->get($uri);

        return $this->api->getResponseBody($response);
    }

    public function deleteLogOutput(
        string $organizationSlug,
        string $pipelineSlug,
        int $buildNumber,
        string $jobId
    ): void {
        $uri = sprintf(
            'organizations/%s/pipelines/%s/builds/%d/jobs/%s/log',
            $organizationSlug,
            $pipelineSlug,
            $buildNumber,
            $jobId
        );
        $response = $this->api->delete($uri);
    }

    public function getEnvironmentVariables(
        string $organizationSlug,
        string $pipelineSlug,
        int $buildNumber,
        string $jobId
    ): array {
        $uri = sprintf(
            'organizations/%s/pipelines/%s/builds/%d/jobs/%s/env',
            $organizationSlug,
            $pipelineSlug,
            $buildNumber,
            $jobId
        );
        $response = $this->api->get($uri);

        return $this->api->getResponseBody($response);
    }
}
