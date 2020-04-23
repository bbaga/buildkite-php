<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Emojis
{
    /**
     * @var RestApiInterface
     */
    private $api;

    /**
     * @var Emoji[]
     */
    private $emojis;

    /**
     * @var string
     */
    private $organizationSlug;

    /**
     * @param RestApiInterface $api
     * @param string $organizationSlug
     * @param Emoji[] $emojis
     */
    public function __construct(RestApiInterface $api, string $organizationSlug, array $emojis = [])
    {
        $this->api = $api;
        $this->organizationSlug = $organizationSlug;
        $this->emojis = $emojis;
    }

    /** @return Emoji[] */
    public function get(): array
    {
        if (count($this->emojis) === 0) {
            $this->emojis = $this->fetch();
        }

        return $this->emojis;
    }

    /**
     * @return Emoji[]
     */
    public function fetch(): array
    {
        $api = $this->api->emoji();

        $emojis = $api->list($this->organizationSlug);

        $list = [];

        /** @var array $emoji */
        foreach ($emojis as $emoji) {
            $list[] = new Emoji($emoji);
        }

        $this->emojis = $list;

        return $list;
    }
}
