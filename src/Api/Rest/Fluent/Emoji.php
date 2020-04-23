<?php

declare(strict_types=1);

namespace bbaga\BuildkiteApi\Api\Rest\Fluent;

final class Emoji
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $aliases;

    public function __construct(array $map = [])
    {
        $this->populate($map);
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
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    private function populate(array $map): void
    {
        $this->name = (string)($map['name'] ?? '');
        $this->url = (string)($map['url'] ?? '');
        $this->aliases = (array)($map['aliases'] ?? []);
    }
}
