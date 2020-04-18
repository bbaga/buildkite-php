<?php
declare(strict_types=1);

namespace BuildkiteApi\Api\Rest;

use BuildkiteApi\Api\RestApi;

final class Artifact
{
    /**
     * @var RestApi
     */
    private $api;

    public function __construct(RestApi $api)
    {
        $this->api = $api;
    }

    public function get(
      string $organizationSlug,
      string $pipelineSlug,
      int $buildNumber,
      string $jobId,
      string $artifactId
    ): array {
        $uri = sprintf('organizations/%s/pipelines/%s/builds/%d/jobs/%s/artifacts/%s', $organizationSlug, $pipelineSlug,
          $buildNumber, $jobId, $artifactId);
        $response = $this->api->get($uri);

        return $this->api->getResponseBody($response);
    }

    public function getDownloadUrl(
      string $organizationSlug,
      string $pipelineSlug,
      int $buildNumber,
      string $jobId,
      string $artifactId
    ): array {
        $uri = sprintf('organizations/%s/pipelines/%s/builds/%d/jobs/%s/artifacts/%s/download', $organizationSlug,
          $pipelineSlug, $buildNumber, $jobId, $artifactId);
        $response = $this->api->get($uri, ['allow_redirects' => false]);

        return $this->api->getResponseBody($response);
    }

    public function getByBuild(string $organizationSlug, string $pipelineSlug, int $buildNumber): array
    {
        $uri = sprintf('organizations/%s/pipelines/%s/builds/%d/artifacts', $organizationSlug, $pipelineSlug,
          $buildNumber);
        $response = $this->api->get($uri);

        return $this->api->getResponseBody($response);
    }

    public function getByJob(string $organizationSlug, string $pipelineSlug, int $buildNumber, string $jobId): array
    {
        $uri = sprintf('organizations/%s/pipelines/%s/builds/%d/jobs/%s/artifacts', $organizationSlug, $pipelineSlug,
          $buildNumber, $jobId);
        $response = $this->api->get($uri);

        return $this->api->getResponseBody($response);
    }
}
