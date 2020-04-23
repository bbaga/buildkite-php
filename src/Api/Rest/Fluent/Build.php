<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;
use function is_array;
use function is_int;

final class Build
{
    /**
     * @var RestApiInterface
     */
    private $api;

    /**
     * @var string
     */
    private $organizationSlug;

    /**
     * @var int
     */
    private $number;

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
    private $web_url;

    /**
     * @var string
     */
    private $state;

    /**
     * @var bool
     */
    private $blocked;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $commit;

    /**
     * @var string
     */
    private $branch;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var array
     */
    private $env;

    /**
     * @var string
     */
    private $source;

    /**
     * @var array
     */
    private $creator;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string
     */
    private $scheduledAt;

    /**
     * @var string
     */
    private $startedAt;

    /**
     * @var string
     */
    private $finishedAt;

    /**
     * @var array
     */
    private $metaData;

    /**
     * @var array
     */
    private $pullRequest;

    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * @var Job[]
     */
    private $jobs;

    public function __construct(RestApiInterface $api, string $organizationSlug, array $map = [])
    {
        $this->api = $api;
        $this->organizationSlug = $organizationSlug;

        if (!isset($map['number']) || !is_int($map['number'])) {
            throw new \InvalidArgumentException(
                'The "number" (representing the build number) must be an integer value'
            );
        }

        if (!isset($map['pipeline']) || (!$map['pipeline'] instanceof Pipeline && !is_array($map['pipeline']))) {
            throw new \InvalidArgumentException('The "pipeline"  must be an array or an instance of ' . Pipeline::class);
        }

        $this->populate($map);
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
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
        return $this->web_url;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getCommit(): string
    {
        return $this->commit;
    }

    /**
     * @return string
     */
    public function getBranch(): string
    {
        return $this->branch;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @return array
     */
    public function getEnv(): array
    {
        return $this->env;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
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
     * @return string
     */
    public function getScheduledAt(): string
    {
        return $this->scheduledAt;
    }

    /**
     * @return string
     */
    public function getStartedAt(): string
    {
        return $this->startedAt;
    }

    /**
     * @return string
     */
    public function getFinishedAt(): string
    {
        return $this->finishedAt;
    }

    /**
     * @return array
     */
    public function getMetaData(): array
    {
        return $this->metaData;
    }

    /**
     * @return array
     */
    public function getPullRequest(): array
    {
        return $this->pullRequest;
    }

    /**
     * @return Pipeline
     */
    public function getPipeline(): Pipeline
    {
        return $this->pipeline;
    }

    /**
     * @return Job[]
     */
    public function getJobs(): array
    {
        return $this->jobs;
    }

    public function getAnnotations(): Annotations
    {
        return new Annotations($this->api, $this->organizationSlug, $this->pipeline->getSlug(), $this->getNumber());
    }

    public function fetch(): self
    {
        $response = $this->api->build()->get($this->organizationSlug, $this->pipeline->getSlug(), $this->getNumber());
        $this->populate($response);

        return $this;
    }

    private function populate(array $map): void
    {
        $this->number = (int)$map['number'];
        $this->id = (string)($map['id'] ?? '');
        $this->url = (string)($map['url'] ?? '');
        $this->web_url = (string)($map['web_url'] ?? '');
        $this->state = (string)($map['state'] ?? '');
        $this->blocked = (bool)($map['blocked'] ?? false);
        $this->message = (string)($map['message'] ?? '');
        $this->commit = (string)($map['commit'] ?? '');
        $this->branch = (string)($map['branch'] ?? '');
        $this->tag = (string)($map['tag'] ?? '');
        $this->env = (array)($map['env'] ?? []);
        $this->source = (string)($map['source'] ?? '');
        $this->creator = (array)($map['creator'] ?? []);
        $this->createdAt = (string)($map['created_at'] ?? '');
        $this->scheduledAt = (string)($map['scheduled_at'] ?? '');
        $this->startedAt = (string)($map['started_at'] ?? '');
        $this->finishedAt = (string)($map['finished_at'] ?? '');
        $this->metaData = (array)($map['meta_data'] ?? []);
        $this->pullRequest = (array)($map['pull_request'] ?? []);

        if (($map['pipeline'] ?? null) instanceof Pipeline) {
            /** @var Pipeline pipeline */
            $this->pipeline = $map['pipeline'];
        } else {
            $this->pipeline = new Pipeline($this->api, $this->organizationSlug, (array) $map['pipeline']);
        }

        $this->jobs = [];

        /** @var array $job */
        foreach ((array)($map['jobs'] ?? []) as $job) {
            $this->jobs[] = new Job($this->api, $this->organizationSlug, $this->pipeline->getSlug(), $this->getNumber(), $job);
        }
    }
}
