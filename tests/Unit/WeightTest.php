<?php

namespace Tests\Unit;

use App\Support\Weight;
use PHPUnit\Framework\TestCase;

/**
 * Unit test: tests ONE class on its own. No database, no HTTP requests, no Laravel app,
 * which is why it extends PHPUnit's TestCase and runs in milliseconds.
 */
class WeightTest extends TestCase
{
    public function test_kg_stays_kg(): void
    {
        $this->assertSame(82.5, Weight::convert(82.5, 'kg'));
    }

    public function test_kg_is_converted_to_lb(): void
    {
        $this->assertEqualsWithDelta(220.462, Weight::convert(100, 'lb'), 0.001);
    }

    public function test_format_drops_useless_decimals(): void
    {
        $this->assertSame('80 kg', Weight::format(80, 'kg'));
        $this->assertSame('82.5 kg', Weight::format(82.5, 'kg'));
        $this->assertSame('220.5 lb', Weight::format(100, 'lb'));
    }

    public function test_format_without_decimals_for_big_totals(): void
    {
        $this->assertSame('12,346 kg', Weight::format(12345.6, 'kg', 0));
    }
}
