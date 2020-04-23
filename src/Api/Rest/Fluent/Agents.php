<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Agents
{
    /**
     * @var RestApiInterface
     */
    private $api;

    /**
     * @var Agent[]
     */
    private $agents;

    /**
     * @var string
     */
    private $organizationSlug;

    /**
     * @param RestApiInterface $api
     * @param string $organizationSlug
     * @param Agent[] $agents
     */
    public function __construct(RestApiInterface $api, string $organizationSlug, array $agents = [])
    {
        $this->api = $api;
        $this->organizationSlug = $organizationSlug;
        $this->agents = $agents;
    }

    /**
     * @param array $queryParameters
     * @return Agent[]
     */
    public function get(array $queryParameters = []): array
    {
        if (count($this->agents) === 0) {
            $this->agents = $this->fetch($queryParameters);
        }

        return $this->agents;
    }

    /**
     * @param array $queryParameters
     * @return Agent[]
     */
    public function fetch(array $queryParameters = []): array
    {
        $api = $this->api->agent();

        $agents = $api->list($this->organizationSlug, $queryParameters);

        $list = [];

        /** @var array $agent */
        foreach ($agents as $agent) {
            $list[] = new Agent($this->api, $this->organizationSlug, $agent);
        }

        $this->agents = $list;

        return $list;
    }
}
