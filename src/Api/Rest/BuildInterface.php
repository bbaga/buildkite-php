<?php

namespace bbaga\BuildkiteApi\Api\Rest;

interface BuildInterface
{
    public function listAll(array $queryParameters = []): array;

    public function getByOrganization(string $organizationSlug, array $queryParameters = []): array;

    public function getByPipeline(string $organizationSlug, string $pipelineSlug, array $queryParameters = []): array;

    public function get(string $organizationSlug, string $pipelineSlug, int $buildNumber): array;

    public function create(string $organizationSlug, string $pipelineSlug, array $body): array;

    public function cancel(string $organizationSlug, string $pipelineSlug, int $buildNumber): array;

    public function rebuild(string $organizationSlug, string $pipelineSlug, int $buildNumber): array;
}
