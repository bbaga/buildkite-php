<?php

namespace bbaga\BuildkiteApi\Api\Rest;

interface EmojiInterface
{
    public function list(string $organizationSlug): array;
}
