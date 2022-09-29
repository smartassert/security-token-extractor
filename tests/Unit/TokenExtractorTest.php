<?php

declare(strict_types=1);

namespace SmartAssert\SecurityTokenExtractor\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use SmartAssert\SecurityTokenExtractor\TokenExtractor;

class TokenExtractorTest extends TestCase
{
    /**
     * @dataProvider extractDataProvider
     */
    public function testExtract(TokenExtractor $extractor, RequestInterface $request, ?string $expected): void
    {
        self::assertSame($expected, $extractor->extract($request));
    }

    /**
     * @return array<mixed>
     */
    public function extractDataProvider(): array
    {
        return [
            'header not present' => [
                'extractor' => new TokenExtractor(),
                'request' => (function () {
                    $request = \Mockery::mock(RequestInterface::class);
                    $request
                        ->shouldReceive('getHeaderLine')
                        ->with(TokenExtractor::DEFAULT_HEADER_NAME)
                        ->andReturn('')
                    ;

                    return $request;
                })(),
                'expected' => null,
            ],
            'header does not start with prefix' => [
                'extractor' => new TokenExtractor(),
                'request' => (function () {
                    $request = \Mockery::mock(RequestInterface::class);
                    $request
                        ->shouldReceive('getHeaderLine')
                        ->with(TokenExtractor::DEFAULT_HEADER_NAME)
                        ->andReturn('prefix-not-present')
                    ;

                    return $request;
                })(),
                'expected' => null,
            ],
            'present, empty' => [
                'extractor' => new TokenExtractor(),
                'request' => (function () {
                    $request = \Mockery::mock(RequestInterface::class);
                    $request
                        ->shouldReceive('getHeaderLine')
                        ->with(TokenExtractor::DEFAULT_HEADER_NAME)
                        ->andReturn(TokenExtractor::DEFAULT_VALUE_PREFIX)
                    ;

                    return $request;
                })(),
                'expected' => null,
            ],
            'present, whitespace' => [
                'extractor' => new TokenExtractor(),
                'request' => (function () {
                    $request = \Mockery::mock(RequestInterface::class);
                    $request
                        ->shouldReceive('getHeaderLine')
                        ->with(TokenExtractor::DEFAULT_HEADER_NAME)
                        ->andReturn(TokenExtractor::DEFAULT_VALUE_PREFIX . '   ')
                    ;

                    return $request;
                })(),
                'expected' => null,
            ],
            'present, non-empty' => [
                'extractor' => new TokenExtractor(),
                'request' => (function () {
                    $request = \Mockery::mock(RequestInterface::class);
                    $request
                        ->shouldReceive('getHeaderLine')
                        ->with(TokenExtractor::DEFAULT_HEADER_NAME)
                        ->andReturn(TokenExtractor::DEFAULT_VALUE_PREFIX . 'token-value')
                    ;

                    return $request;
                })(),
                'expected' => 'token-value',
            ],
        ];
    }
}
