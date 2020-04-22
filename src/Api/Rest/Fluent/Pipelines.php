<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Pipelines
{
    /**
     * @var RestApiInterface
     */
    private $api;

    /**
     * @var Pipeline[]
     */
    private $pipelines;

    /**
     * @var string
     */
    private $organizationSlug;

    /**
     * Pipelines constructor.
     * @param RestApiInterface $api
     * @param string $organizationSlug
     * @param Pipeline[] $pipelines
     */
    public function __construct(RestApiInterface $api, string $organizationSlug, array $pipelines = [])
    {
        $this->api = $api;
        $this->organizationSlug = $organizationSlug;
        $this->pipelines = $pipelines;
    }

    /** @return Pipeline[] */
    public function get(): array
    {
        if (count($this->pipelines) === 0) {
            $this->pipelines = $this->fetch();
        }

        return $this->pipelines;
    }

    /**
     * @return Pipeline[]
     */
    public function fetch(): array
    {
        $pipelines = $this->api->pipeline()->list($this->organizationSlug);

        $list = [];

        /** @var array $pipeline */
        foreach ($pipelines as $pipeline) {
            $list[] = new Pipeline($this->api, $this->organizationSlug, $pipeline);
        }

        $this->pipelines = $list;

        return $list;
    }
}
