<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApi;

final class Pipeline
{
    /**
     * @var RestApi
     */
    private $api;

    public function __construct(RestApi $api)
    {
        $this->api = $api;
    }

    public function list(string $organizationSlug): array
    {
        $response = $this->api->get(sprintf('organizations/%s/pipelines', $organizationSlug));

        return $this->api->getResponseBody($response);
    }

    public function get(string $organizationSlug, string $pipelineSlug): array
    {
        $uri = sprintf('organizations/%s/pipelines/%s', $organizationSlug, $pipelineSlug);
        $response = $this->api->get($uri);

        return $this->api->getResponseBody($response);
    }

    public function create(string $organizationSlug, array $body): array
    {
        $uri = sprintf('organizations/%s/pipelines', $organizationSlug);
        $response = $this->api->post($uri, $body);

        return $this->api->getResponseBody($response);
    }

    public function update(string $organizationSlug, string $pipelineSlug, array $body): array
    {
        $uri = sprintf('organizations/%s/pipelines/%s', $organizationSlug, $pipelineSlug);
        $response = $this->api->patch($uri, $body);

        return $this->api->getResponseBody($response);
    }

    public function delete(string $organizationSlug, string $pipelineSlug): void
    {
        $uri = sprintf('organizations/%s/pipelines/%s', $organizationSlug, $pipelineSlug);
        $this->api->delete($uri);
    }
}
