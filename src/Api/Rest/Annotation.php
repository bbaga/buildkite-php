<?php
declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest;

use bbaga\BuildkiteApi\Api\RestApi;

final class Annotation
{
    /**
     * @var RestApi
     */
    private $api;

    public function __construct(RestApi $api)
    {
        $this->api = $api;
    }

    public function list(string $organizationSlug, string $pipelineSlug, int $buildNumber): array
    {
        $uri = sprintf(
            'organizations/%s/pipelines/%s/builds/%d/annotations',
            $organizationSlug,
            $pipelineSlug,
            $buildNumber
        );
        $response = $this->api->get($uri);

        return $this->api->getResponseBody($response);
    }
}
