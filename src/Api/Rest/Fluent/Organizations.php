<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Organizations
{
    /**
     * @var RestApiInterface
     */
    private $api;

    /**
     * @var Organization[]
     */
    private $organizations;

    /**
     * Organizations constructor.
     * @param RestApiInterface $api
     * @param Organization[] $organizations
     */
    public function __construct(RestApiInterface $api, array $organizations = [])
    {
        $this->api = $api;
        $this->organizations = $organizations;
    }

    /** @return Organization[] */
    public function get(): array
    {
        if (count($this->organizations) === 0) {
            $this->organizations = $this->fetch();
        }

        return $this->organizations;
    }

    /**
     * @return Organization[]
     */
    public function fetch(): array
    {
        $organizations = $this->api->getResponseBody($this->api->get('organizations'));

        $list = [];

        /** @var array $organization */
        foreach ($organizations as $organization) {
            $list[] = new Organization($this->api, $organization);
        }

        $this->organizations = $list;

        return $list;
    }
}
