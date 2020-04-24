<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class Agent
{
    /**
     * @var RestApiInterface
     */
    private $api;

    /**
     * @var Organization
     */
    private $organization;

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
    private $connectionState;

    /**
     * @var string
     */
    private $hostname;

    /**
     * @var string
     */
    private $ipAddress;

    /**
     * @var string
     */
    private $userAgent;

    /**
     * @var string
     */
    private $version;

    /**
     * @var array
     */
    private $creator;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var Job|null
     */
    private $job;

    /**
     * @var string
     */
    private $lastJobFinishedAt;

    /**
     * @var string
     */
    private $priority;

    /**
     * @var array
     */
    private $metaData;

    public function __construct(RestApiInterface $api, Organization $organization, array $map = [])
    {
        $this->api = $api;
        $this->organization = $organization;

        if (!isset($map['id']) || !is_string($map['id'])) {
            throw new \InvalidArgumentException(
                'The "id" (representing the agent id) must be an string value'
            );
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
    public function getConnectionState(): string
    {
        return $this->connectionState;
    }

    /**
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return array
     */
    public function getCreator(): array
    {
        return $this->creator;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return Job|null
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @return string
     */
    public function getLastJobFinishedAt(): string
    {
        return $this->lastJobFinishedAt;
    }

    /**
     * @return string
     */
    public function getPriority(): string
    {
        return $this->priority;
    }

    /**
     * @return array
     */
    public function getMetaData(): array
    {
        return $this->metaData;
    }

    public function stop(bool $force = false): void
    {
        $this->api->agent()->stop($this->organization->getSlug(), $this->getId(), $force);
    }

    private function populate(array $map): void
    {
        $this->id = (string)($map['id'] ?? '');
        $this->url = (string)($map['url'] ?? '');
        $this->webUrl = (string)($map['webUrl'] ?? '');
        $this->name = (string)($map['name'] ?? '');
        $this->connectionState = (string)($map['connectionState'] ?? '');
        $this->hostname = (string)($map['hostname'] ?? '');
        $this->ipAddress = (string)($map['ipAddress'] ?? '');
        $this->userAgent = (string)($map['userAgent'] ?? '');
        $this->version = (string)($map['version'] ?? '');
        $this->creator = (array)($map['creator'] ?? []);
        $this->createdAt = (string)($map[''] ?? '');
        $this->lastJobFinishedAt = (string)($map['last_job_finished_at'] ?? '');
        $this->priority = (string)($map['priority'] ?? '');
        $this->metaData = (array)($map['meta_data'] ?? '');

        if ($map['job'] ?? null instanceof Job) {
            /** @var Job job */
            $this->job = $map['job'];
        } else {
            preg_match(
                '/^.+\/pipelines\/(?<pipeline>.+)\/builds\/(?<buildNumber>.+)$/',
                (string)($map['job']['build_url'] ?? ''),
                $matches
            );

            if (isset($map['job']['id'], $matches['pipeline'], $matches['buildNumber'])) {
                $pipeline = new Pipeline($this->api, $this->organization, ['slug' => $matches['pipeline']]);

                $build = new Build(
                    $this->api,
                    $this->organization,
                    [
                        'number' => $matches['buildNumber'],
                        'pipeline' => $pipeline
                    ]
                );

                $this->job = new Job(
                    $this->api,
                    $build,
                    (array) $map['job']
                );
            }
        }
    }
}
