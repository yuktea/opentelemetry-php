<?php

declare(strict_types=1);

namespace OpenTelemetry\Tests\Unit\SDK\Common\Environment\Parser;

use InvalidArgumentException;
use OpenTelemetry\SDK\Common\Environment\Parser\MapParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \OpenTelemetry\SDK\Common\Environment\Parser\MapParser
 */
class MapParserTest extends TestCase
{
    private const MAP_VALUES = [
        'empty' => [
            '',
            [],
        ],
        'single pair' => [
            'foo=bar',
            ['foo' => 'bar'],
        ],
        'multiple pairs' => [
            'foo=bar,bar=baz,baz=foo',
            ['foo' => 'bar', 'bar' => 'baz', 'baz' => 'foo'],
        ],
        'multiple items with spaces' => [
            'foo =bar,bar= baz, baz = foo',
            ['foo' => 'bar', 'bar' => 'baz', 'baz' => 'foo'],
        ],
    ];

    private const INVALID_VALUES = [
        'string' => ['foobar'],
        'no equals' => ['foo=bar,barbaz'],
    ];

    /**
     * @dataProvider mapValueProvider
     */
    public function test_map_values_return_array(string $value, array $expected): void
    {
        $this->assertSame(
            MapParser::parse($value),
            $expected
        );
    }

    /**
     * @dataProvider invalidValueProvider
     */
    public function test_invalid_values_throw_exception(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        MapParser::parse($value);
    }

    public function mapValueProvider(): array
    {
        return self::MAP_VALUES;
    }

    public function invalidValueProvider(): array
    {
        return self::INVALID_VALUES;
    }
}
