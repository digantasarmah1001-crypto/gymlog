<?php

namespace App\Support;

/**
 * Weights are always STORED in kg. This class only changes how they are SHOWN:
 * in kg or in lb, depending on the athlete's choice (saved in the session by UnitController).
 */
class Weight
{
    public const UNITS = ['kg', 'lb'];

    public const LB_PER_KG = 2.20462;

    /**
     * The unit chosen in this browser session ('kg' if nothing was chosen yet).
     */
    public static function unit(): string
    {
        return session('unit', 'kg');
    }

    /**
     * Convert kg to the given unit.
     */
    public static function convert(float $kg, string $unit = 'kg'): float
    {
        return $unit === 'lb' ? $kg * self::LB_PER_KG : $kg;
    }

    /**
     * "82.5 kg" or "181.9 lb". Trailing ".0" is dropped ("80 kg", not "80.0 kg").
     */
    public static function format(float $kg, ?string $unit = null, int $decimals = 1): string
    {
        $unit ??= self::unit();
        $number = number_format(self::convert($kg, $unit), $decimals);

        if ($decimals > 0) {
            $number = rtrim(rtrim($number, '0'), '.');
        }

        return $number.' '.$unit;
    }
}
