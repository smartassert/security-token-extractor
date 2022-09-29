<?php

declare(strict_types=1);

namespace SmartAssert\SecurityTokenExtractor;

use Psr\Http\Message\RequestInterface;

class TokenExtractor
{
    public const DEFAULT_HEADER_NAME = 'Authorization';
    public const DEFAULT_VALUE_PREFIX = 'Bearer ';

    public function __construct(
        private readonly string $headerName = self::DEFAULT_HEADER_NAME,
        private readonly string $valuePrefix = self::DEFAULT_VALUE_PREFIX,
    ) {
    }

    public function extract(RequestInterface $request): ?string
    {
        $authorizationHeader = $request->getHeaderLine($this->headerName);
        $authorizationHeader = trim($authorizationHeader);
        if ('' === $authorizationHeader) {
            return null;
        }

        if (false === str_starts_with($authorizationHeader, $this->valuePrefix)) {
            return null;
        }

        return substr($authorizationHeader, strlen($this->valuePrefix));
    }
}
