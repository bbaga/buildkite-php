<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Builds
{
    /**
     * @var RestApiInterface
     */
    private $api;

    /**
     * @var Build[]
     */
    private $builds;

    /**
     * @var string
     */
    private $organizationSlug;

    /**
     * @var string|null
     */
    private $pipelineSlug;

    /**
     * @param RestApiInterface $api
     * @param string $organizationSlug
     * @param string|null $pipelineSlug
     * @param Build[] $builds
     */
    public function __construct(RestApiInterface $api, string $organizationSlug, string $pipelineSlug = null, array $builds = [])
    {
        $this->api = $api;
        $this->organizationSlug = $organizationSlug;
        $this->pipelineSlug = $pipelineSlug;
        $this->builds = $builds;
    }

    /**
     * @param array $queryParameters
     * @return Build[]
     */
    public function get(array $queryParameters = []): array
    {
        if (count($this->builds) === 0) {
            $this->builds = $this->fetch($queryParameters);
        }

        return $this->builds;
    }

    /**
     * @param array $queryParameters
     * @return Build[]
     */
    public function fetch(array $queryParameters = []): array
    {
        $api = $this->api->build();

        if ($this->pipelineSlug === null) {
            $builds = $api->getByOrganization($this->organizationSlug, $queryParameters);
        } else {
            $builds = $api->getByPipeline($this->organizationSlug, $this->pipelineSlug, $queryParameters);
        }

        $list = [];

        /** @var array $build */
        foreach ($builds as $build) {
            $list[] = new Build($this->api, $this->organizationSlug, $build);
        }

        $this->builds = $list;

        return $list;
    }
}
