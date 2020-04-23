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

    /** @return Build[] */
    public function get(): array
    {
        if (count($this->builds) === 0) {
            $this->builds = $this->fetch();
        }

        return $this->builds;
    }

    /**
     * @return Build[]
     */
    public function fetch(int $limit = 500, int $page = 1): array
    {
        $api = $this->api->build();

        if ($this->pipelineSlug === null) {
            $builds = $api->getByOrganization($this->organizationSlug, ['page' => $page, 'per_page' => $limit]);
        } else {
            $builds = $api->getByPipeline($this->organizationSlug, $this->pipelineSlug, ['page' => $page, 'per_page' => $limit]);
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
