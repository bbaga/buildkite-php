<?php
declare(strict_types=1);

namespace BuildkiteApi\Api\Rest;

use BuildkiteApi\Api\RestApi;

final class Build
{
    /**
     * @var RestApi
     */
    private $api;

    public function __construct(RestApi $api)
    {
        $this->api = $api;
    }

    public function listAll(array $queryParameters): array
    {
        $response = $this->api->get('builds', ['query' => $queryParameters]);

        return $this->api->getResponseBody($response);
    }

    public function getByOrganization(string $organizationSlug, array $queryParameters): array
    {
        $response = $this->api->get(
            sprintf('organizations/%s/builds', $organizationSlug),
            ['query' => $queryParameters]
        );

        return $this->api->getResponseBody($response);
    }

    public function getBySlug(string $organizationSlug, string $pipelineSlug, array $queryParameters): array
    {
        $uri = sprintf('organizations/%s/pipelines/%s/builds', $organizationSlug, $pipelineSlug);
        $response = $this->api->get($uri, ['query' => $queryParameters]);

        return $this->api->getResponseBody($response);
    }

    public function get(string $organizationSlug, string $pipelineSlug, int $buildNumber): array
    {
        $uri = sprintf('organizations/%s/pipelines/%s/builds/%d', $organizationSlug, $pipelineSlug, $buildNumber);
        $response = $this->api->get($uri);

        return $this->api->getResponseBody($response);
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

    public function delete(
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
}
