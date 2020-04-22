<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;
use function is_string;

final class Pipeline
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
    private $description;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $repository;

    /**
     * @var string
     */
    private $branchConfiguration;

    /**
     * @var string
     */
    private $defaultBranch;

    /**
     * @var bool|null
     */
    private $skipQueuedBranchBuilds;

    /**
     * @var string|null
     */
    private $skipQueuedBranchBuildsFilter;

    /**
     * @var bool|null
     */
    private $cancelRunningBranchBuilds;

    /**
     * @var string|null
     */
    private $cancelRunningBranchBuildsFilter;

    /**
     * @var array
     */
    private $provider;

    /**
     * @var string
     */
    private $buildsUrl;

    /**
     * @var string
     */
    private $badgeUrl;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var array
     */
    private $env;

    /**
     * @var int
     */
    private $scheduledBuildsCount;

    /**
     * @var int
     */
    private $runningBuildsCount;

    /**
     * @var int
     */
    private $scheduledJobsCount;

    /**
     * @var int
     */
    private $runningJobsCount;

    /**
     * @var int
     */
    private $waitingJobsCount;

    /**
     * @var string
     */
    private $visibility;

    /**
     * @var string
     */
    private $configuration;

    /**
     * @var array
     */
    private $steps;

    public function __construct(RestApiInterface $api, string $organizationSlug, array $map = [])
    {
        $this->api = $api;
        $this->organizationSlug = $organizationSlug;

        if (!isset($map['slug']) || !is_string($map['slug'])) {
            throw new \InvalidArgumentException('The "slug" (representing the pipeline\'s slug) must be a string value');
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
    public function getDescription(): string
    {
        return $this->description;
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
    public function getRepository(): string
    {
        return $this->repository;
    }

    /**
     * @return string
     */
    public function getBranchConfiguration(): string
    {
        return $this->branchConfiguration;
    }

    /**
     * @return string
     */
    public function getDefaultBranch(): string
    {
        return $this->defaultBranch;
    }

    /**
     * @return bool|null
     */
    public function getSkipQueuedBranchBuilds(): ?bool
    {
        return $this->skipQueuedBranchBuilds;
    }

    /**
     * @return string|null
     */
    public function getSkipQueuedBranchBuildsFilter(): ?string
    {
        return $this->skipQueuedBranchBuildsFilter;
    }

    /**
     * @return bool|null
     */
    public function getCancelRunningBranchBuilds(): ?bool
    {
        return $this->cancelRunningBranchBuilds;
    }

    /**
     * @return string|null
     */
    public function getCancelRunningBranchBuildsFilter(): ?string
    {
        return $this->cancelRunningBranchBuildsFilter;
    }

    /**
     * @return array
     */
    public function getProvider(): array
    {
        return $this->provider;
    }

    /**
     * @return string
     */
    public function getBuildsUrl(): string
    {
        return $this->buildsUrl;
    }

    /**
     * @return string
     */
    public function getBadgeUrl(): string
    {
        return $this->badgeUrl;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return array
     */
    public function getEnv(): array
    {
        return $this->env;
    }

    /**
     * @return int
     */
    public function getScheduledBuildsCount(): int
    {
        return $this->scheduledBuildsCount;
    }

    /**
     * @return int
     */
    public function getRunningBuildsCount(): int
    {
        return $this->runningBuildsCount;
    }

    /**
     * @return int
     */
    public function getScheduledJobsCount(): int
    {
        return $this->scheduledJobsCount;
    }

    /**
     * @return int
     */
    public function getRunningJobsCount(): int
    {
        return $this->runningJobsCount;
    }

    /**
     * @return int
     */
    public function getWaitingJobsCount(): int
    {
        return $this->waitingJobsCount;
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * @return array
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    /**
     * @return string
     */
    public function getConfiguration(): string
    {
        return $this->configuration;
    }

    public function fetch(): self
    {
        $response = $this->api->pipeline()->get($this->organizationSlug, $this->getSlug());
        $this->populate($response);

        return $this;
    }

    public function getBuilds(): Builds
    {
        return new Builds($this->api, $this->organizationSlug, $this->getSlug());
    }

    private function populate(array $map): void
    {
        $this->id = (string) ($map['id'] ?? '');
        $this->url = (string) ($map['url'] ?? '');
        $this->webUrl = (string) ($map['web_url'] ?? '');
        $this->name = (string) ($map['name'] ?? '');
        $this->description = (string) ($map['description'] ?? '');
        $this->slug = (string) ($map['slug'] ?? '');
        $this->repository = (string) ($map['repository'] ?? '');
        $this->branchConfiguration = (string) ($map['branch_configuration'] ?? '');
        $this->defaultBranch = (string) ($map['default_branch'] ?? '');
        $this->skipQueuedBranchBuilds = (bool) ($map['skip_queued_branch_builds'] ?? false);
        $this->skipQueuedBranchBuildsFilter = (string) ($map['skip_queued_branch_builds_filter'] ?? '');
        $this->cancelRunningBranchBuilds = (bool) ($map['cancel_running_branch_builds'] ?? false);
        $this->cancelRunningBranchBuildsFilter = (string) ($map['cancel_running_branch_builds_filter'] ?? '');
        $this->provider = (array) ($map['provider'] ?? []);
        $this->buildsUrl = (string) ($map['builds_url'] ?? '');
        $this->badgeUrl = (string) ($map['badge_url'] ?? '');
        $this->createdAt = (string) ($map['created_at'] ?? '');
        $this->env = (array) ($map['env'] ?? []);
        $this->scheduledBuildsCount = (int) ($map['scheduled_builds_count'] ?? 0);
        $this->runningBuildsCount = (int) ($map['running_builds_count'] ?? 0);
        $this->scheduledJobsCount = (int) ($map['scheduled_jobs_count'] ?? 0);
        $this->runningJobsCount = (int) ($map['running_jobs_count'] ?? 0);
        $this->waitingJobsCount = (int) ($map['waiting_jobs_count'] ?? 0);
        $this->visibility = (string) ($map['visibility'] ?? '');
        $this->steps = (array) ($map['steps'] ?? []);
        $this->configuration = (string) ($map['configuration'] ?? '');
    }
}
