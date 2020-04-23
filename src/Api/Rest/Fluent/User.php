<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

use bbaga\BuildkiteApi\Api\RestApiInterface;

final class User
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
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $avatarUrl;

    /**
     * @var string
     */
    private $createdAt;

    public function __construct(RestApiInterface $api, array $map = [])
    {
        $this->api = $api;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
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
        $response = $this->api->user()->whoami();
        $this->populate($response);

        return $this;
    }

    private function populate(array $map): void
    {
        $this->id = (string)($map['id'] ?? '');
        $this->name = (string)($map['name'] ?? '');
        $this->email = (string)($map['email'] ?? '');
        $this->avatarUrl = (string)($map['avatar_url'] ?? '');
        $this->createdAt = (string)($map['created_at'] ?? '');
    }
}
