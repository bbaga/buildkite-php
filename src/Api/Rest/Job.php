<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Job
{
    /**
     * @var RestApiInterface
     */
    private $api;

    public function __construct(RestApiInterface $api)
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

    public function unblock(
        string $organizationSlug,
        string $pipelineSlug,
        int $buildNumber,
        string $jobId,
        array $fields = [],
        string $userId = null
    ): array {
        $uri = sprintf(
            'organizations/%s/pipelines/%s/builds/%d/jobs/%s/unlock',
            $organizationSlug,
            $pipelineSlug,
            $buildNumber,
            $jobId
        );

        $body = [];

        if ($userId !== null) {
            $body['unblocker'] = $userId;
        }

        if (count($fields) > 0) {
            $body['fields'] = $fields;
        }

        $response = $this->api->put(
            $uri,
            $body
        );

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

        $this->api->delete($uri);
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
