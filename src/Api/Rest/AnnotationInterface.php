<?php

namespace bbaga\BuildkiteApi\Api\Rest;

interface AnnotationInterface
{
    public function list(
        string $organizationSlug,
        string $pipelineSlug,
        int $buildNumber,
        array $queryParameters = []
    ): array;
}