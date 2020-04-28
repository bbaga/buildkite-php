<?php

namespace bbaga\BuildkiteApi\Api\Rest;

interface ArtifactInterface
{
    public function get(
        string $organizationSlug,
        string $pipelineSlug,
        int $buildNumber,
        string $jobId,
        string $artifactId
    ): array;

    public function getDownloadUrl(
        string $organizationSlug,
        string $pipelineSlug,
        int $buildNumber,
        string $jobId,
        string $artifactId
    ): array;

    public function getByBuild(
        string $organizationSlug,
        string $pipelineSlug,
        int $buildNumber,
        array $queryParameters = []
    ): array;

    public function getByJob(
        string $organizationSlug,
        string $pipelineSlug,
        int $buildNumber,
        string $jobId,
        array $queryParameters = []
    ): array;

    public function delete(
        string $organizationSlug,
        string $pipelineSlug,
        int $buildNumber,
        string $jobId,
        string $artifactId
    ): void;
}