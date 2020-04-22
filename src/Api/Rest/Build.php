<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApi;

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

    public function listAll(array $queryParameters = []): array
    {
        $response = $this->api->get('builds', ['query' => $queryParameters]);

        return $this->api->getResponseBody($response);
    }

    public function getByOrganization(string $organizationSlug, array $queryParameters = []): array
    {
        $response = $this->api->get(
            sprintf('organizations/%s/builds', $organizationSlug),
            ['query' => $queryParameters]
        );

        return $this->api->getResponseBody($response);
    }

    public function getByPipeline(string $organizationSlug, string $pipelineSlug, array $queryParameters = []): array
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

    public function create(string $organizationSlug, string $pipelineSlug, array $body): array
    {
        $uri = sprintf('organizations/%s/pipelines/%s/builds', $organizationSlug, $pipelineSlug);
        $response = $this->api->post($uri, $body);

        return $this->api->getResponseBody($response);
    }

    public function cancel(string $organizationSlug, string $pipelineSlug, int $buildNumber): array
    {
        $uri = sprintf(
            'organizations/%s/pipelines/%s/builds/%d/cancel',
            $organizationSlug,
            $pipelineSlug,
            $buildNumber
        );
        $response = $this->api->put($uri);

        return $this->api->getResponseBody($response);
    }

    public function rebuild(string $organizationSlug, string $pipelineSlug, int $buildNumber): array
    {
        $uri = sprintf(
            'organizations/%s/pipelines/%s/builds/%d/rebuild',
            $organizationSlug,
            $pipelineSlug,
            $buildNumber
        );
        $response = $this->api->put($uri);

        return $this->api->getResponseBody($response);
    }
}
