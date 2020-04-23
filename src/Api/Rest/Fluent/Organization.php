<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Organization
{
    /**
     * @var RestApiInterface
     */
    private $api;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $webUrl;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $pipelinesUrl;

    /**
     * @var string
     */
    private $agentsUrl;

    /**
     * @var string
     */
    private $emojisUrl;

    /**
     * @var string
     */
    private $createdAt;

    public function __construct(RestApiInterface $api, array $map = [])
    {
        $this->api = $api;

        if (!isset($map['slug']) || !is_string($map['slug'])) {
            throw new \InvalidArgumentException('The "slug" (representing the organization\'s slug) must be a string value');
        }

        $this->populate($map);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getWebUrl(): string
    {
        return $this->webUrl;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getPipelinesUrl(): string
    {
        return $this->pipelinesUrl;
    }

    /**
     * @return string
     */
    public function getAgentsUrl(): string
    {
        return $this->agentsUrl;
    }

    /**
     * @return string
     */
    public function getEmojisUrl(): string
    {
        return $this->emojisUrl;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function fetch(): self
    {
        $response = $this->api->organization()->get($this->getSlug());
        $this->populate($response);

        return $this;
    }

    public function getPipelines(): Pipelines
    {
        return new Pipelines($this->api, $this->getSlug());
    }

    public function getEmojis(): Emojis
    {
        return new Emojis($this->api, $this->getSlug());
    }

    public function getAgents(): Agents
    {
        return new Agents($this->api, $this->getSlug());
    }

    private function populate(array $map): void
    {
        $this->id = (string) ($map['id'] ?? '');
        $this->url = (string) ($map['url'] ?? '');
        $this->webUrl = (string) ($map['web_url'] ?? '');
        $this->name = (string) ($map['name'] ?? '');
        $this->slug = (string) ($map['slug'] ?? '');
        $this->pipelinesUrl = (string) ($map['pipelines_url'] ?? '');
        $this->agentsUrl = (string) ($map['agents_url'] ?? '');
        $this->emojisUrl = (string) ($map['emojis_url'] ?? '');
        $this->createdAt = (string) ($map['created_at'] ?? '');
    }
}
