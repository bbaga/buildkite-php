<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Annotation implements AnnotationInterface
{
    /**
     * @var RestApiInterface
     */
    private $api;

    public function __construct(RestApiInterface $api)
    {
        $this->api = $api;
    }

    public function list(string $organizationSlug, string $pipelineSlug, int $buildNumber, array $queryParameters = []): array
    {
        $uri = sprintf(
            'organizations/%s/pipelines/%s/builds/%d/annotations',
            $organizationSlug,
            $pipelineSlug,
            $buildNumber
        );

        $response = $this->api->get($uri, ['query' => $queryParameters]);

        return $this->api->getResponseBody($response);
    }
}
