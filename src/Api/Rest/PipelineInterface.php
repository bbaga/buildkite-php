<?php

namespace bbaga\BuildkiteApi\Api\Rest;

interface PipelineInterface
{
    public function list(string $organizationSlug, array $queryParameters = []): array;

    public function get(string $organizationSlug, string $pipelineSlug): array;

    public function create(string $organizationSlug, array $body): array;

    public function update(string $organizationSlug, string $pipelineSlug, array $body): array;

    public function delete(string $organizationSlug, string $pipelineSlug): void;
}
