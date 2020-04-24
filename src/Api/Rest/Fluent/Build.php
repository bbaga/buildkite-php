<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;
use function is_array;

final class Build
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
     * @var User
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

    public function __construct(RestApiInterface $api, Organization $organization, array $map = [])
    {
        $this->api = $api;
        $this->organization = $organization;

        if (!isset($map['pipeline']) || (!$map['pipeline'] instanceof Pipeline && !is_array($map['pipeline']))) {
            throw new \InvalidArgumentException('The "pipeline" must be an array or an instance of ' . Pipeline::class);
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
     * @return User
     */
    public function getCreator(): User
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
     * @return string
     */
    public function getPipelineSlug(): string
    {
        return $this->pipeline->getSlug();
    }

    /**
     * @return Organization
     */
    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    /**
     * @return string
     */
    public function getOrganizationSlug(): string
    {
        return $this->organization->getSlug();
    }

    /**
     * @return Job[]
     */
    public function getJobs(): array
    {
        return $this->jobs;
    }

    /**
     * @return Annotation[]
     */
    public function getAnnotations(array $queryParameters = []): array
    {
        $api = $this->api->annotation();
        $annotations = $api->list($this->getOrganizationSlug(), $this->getPipelineSlug(), $this->getNumber(), $queryParameters);

        $list = [];

        /** @var array $annotation */
        foreach ($annotations as $annotation) {
            $list[] = new Annotation($annotation);
        }

        return $list;
    }

    /**
     * @return Artifact[]
     */
    public function getArtifacts(): array
    {
        $result = $this->api->artifact()->getByBuild(
            $this->getOrganizationSlug(),
            $this->getPipelineSlug(),
            $this->getNumber()
        );

        $artifacts = [];

        /** @var array $artifact */
        foreach ($result as $artifact) {
            $artifacts[] = new Artifact($this->api, $this, $artifact);
        }

        return $artifacts;
    }

    public function create(array $data): self
    {
        $result = $this->api->build()->create(
            $this->getOrganizationSlug(),
            $this->getPipelineSlug(),
            $data
        );

        $this->populate($result);

        return $this;
    }

    public function cancel(): self
    {
        $result = $this->api->build()->cancel(
            $this->getOrganizationSlug(),
            $this->getPipelineSlug(),
            $this->getNumber()
        );

        $this->populate($result);

        return $this;
    }

    public function rebuild(): self
    {
        $result = $this->api->build()->rebuild(
            $this->getOrganizationSlug(),
            $this->getPipelineSlug(),
            $this->getNumber()
        );

        $this->populate($result);

        return $this;
    }

    public function fetch(): self
    {
        $response = $this->api->build()->get(
            $this->getOrganization()->getSlug(),
            $this->getPipelineSlug(),
            $this->getNumber()
        );
        $this->populate($response);

        return $this;
    }

    private function populate(array $map): void
    {
        $this->number = (int)($map['number'] ?? 0);
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
        $this->createdAt = (string)($map['created_at'] ?? '');
        $this->scheduledAt = (string)($map['scheduled_at'] ?? '');
        $this->startedAt = (string)($map['started_at'] ?? '');
        $this->finishedAt = (string)($map['finished_at'] ?? '');
        $this->metaData = (array)($map['meta_data'] ?? []);
        $this->pullRequest = (array)($map['pull_request'] ?? []);

        /** @var array|User creator */
        $creatorData = $map['creator'] ?? [];

        if ($creatorData instanceof User) {
            /** @var User $creatorData */
            $this->creator = $creatorData;
        } else {
            $this->creator = new User($this->api, $creatorData);
        }

        /** @var array|Pipeline $pipelineData */
        $pipelineData = $map['pipeline'] ?? [];

        if ($pipelineData instanceof Pipeline) {
            /** @var Pipeline $pipelineData */
            $this->pipeline = $pipelineData;
        } else {
            $this->pipeline = new Pipeline($this->api, $this->organization, (array)$pipelineData);
        }

        $this->jobs = [];

        /** @var array $job */
        foreach ((array)($map['jobs'] ?? []) as $job) {
            $this->jobs[] = new Job($this->api, $this, $job);
        }
    }
}
