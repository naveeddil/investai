<?php

namespace App\Services;

/**
 * Transparent, illustrative portfolio risk arithmetic.
 *
 * The assumptions below are round, long-run style figures chosen for teaching,
 * not forecasts. The same formulas are mirrored in resources/js/app.js so the
 * calculator works instantly in the browser; keep the two in step.
 */
class RiskEstimator
{
    private const BAD_YEAR_Z = 1.645;

    private const VERY_BAD_YEAR_Z = 2.326;

    /**
     * @return array{classes: array<string, array{label: string, expectedReturn: float, volatility: float, growth: bool}>, correlations: array<string, array<string, float>>}
     */
    public static function assumptions(): array
    {
        return [
            'classes' => [
                'equities' => ['label' => 'Shares (equities)', 'expectedReturn' => 6.5, 'volatility' => 16.0, 'growth' => true],
                'bonds' => ['label' => 'Bonds', 'expectedReturn' => 3.0, 'volatility' => 6.0, 'growth' => false],
                'cash' => ['label' => 'Cash', 'expectedReturn' => 2.0, 'volatility' => 0.5, 'growth' => false],
                'property' => ['label' => 'Property', 'expectedReturn' => 5.0, 'volatility' => 13.0, 'growth' => true],
                'alternatives' => ['label' => 'Commodities & alternatives', 'expectedReturn' => 4.5, 'volatility' => 15.0, 'growth' => true],
            ],
            'correlations' => [
                'equities' => ['equities' => 1.0, 'bonds' => 0.2, 'cash' => 0.0, 'property' => 0.7, 'alternatives' => 0.5],
                'bonds' => ['equities' => 0.2, 'bonds' => 1.0, 'cash' => 0.2, 'property' => 0.3, 'alternatives' => 0.1],
                'cash' => ['equities' => 0.0, 'bonds' => 0.2, 'cash' => 1.0, 'property' => 0.0, 'alternatives' => 0.0],
                'property' => ['equities' => 0.7, 'bonds' => 0.3, 'cash' => 0.0, 'property' => 1.0, 'alternatives' => 0.4],
                'alternatives' => ['equities' => 0.5, 'bonds' => 0.1, 'cash' => 0.0, 'property' => 0.4, 'alternatives' => 1.0],
            ],
        ];
    }

    /**
     * @param  array<string, float|int>  $allocation  Percentage per asset class, summing to roughly 100.
     * @return array{expectedReturn: float, volatility: float, badYear: float, veryBadYear: float, medianMultiple: float, lowMultiple: float, highMultiple: float, largestHolding: float, growthShare: float, diversificationRatio: float, band: string, toleranceStatus: string, flags: list<string>}
     */
    public static function estimate(array $allocation, int $years, int $tolerancePercent): array
    {
        $assumptions = self::assumptions();
        $weights = [];

        foreach ($assumptions['classes'] as $key => $class) {
            $weights[$key] = ((float) ($allocation[$key] ?? 0)) / 100;
        }

        $mean = 0.0;
        $weightedVolatility = 0.0;
        $variance = 0.0;
        $growthShare = 0.0;

        foreach ($assumptions['classes'] as $key => $class) {
            $mean += $weights[$key] * $class['expectedReturn'] / 100;
            $weightedVolatility += $weights[$key] * $class['volatility'] / 100;
            $growthShare += $class['growth'] ? $weights[$key] : 0.0;

            foreach ($assumptions['classes'] as $otherKey => $other) {
                $variance += $weights[$key] * $weights[$otherKey]
                    * ($class['volatility'] / 100) * ($other['volatility'] / 100)
                    * $assumptions['correlations'][$key][$otherKey];
            }
        }

        $volatility = sqrt($variance);
        $badYear = ($mean - self::BAD_YEAR_Z * $volatility) * 100;
        $veryBadYear = ($mean - self::VERY_BAD_YEAR_Z * $volatility) * 100;

        $medianGrowth = $mean - ($volatility ** 2) / 2;
        $spread = $volatility * sqrt($years);
        $largestHolding = max($weights) * 100;
        $loss = max(0.0, -$badYear);

        return [
            'expectedReturn' => round($mean * 100, 2),
            'volatility' => round($volatility * 100, 2),
            'badYear' => round($badYear, 2),
            'veryBadYear' => round($veryBadYear, 2),
            'medianMultiple' => round(exp($medianGrowth * $years), 4),
            'lowMultiple' => round(exp($medianGrowth * $years - self::BAD_YEAR_Z * $spread), 4),
            'highMultiple' => round(exp($medianGrowth * $years + self::BAD_YEAR_Z * $spread), 4),
            'largestHolding' => round($largestHolding, 1),
            'growthShare' => round($growthShare * 100, 1),
            'diversificationRatio' => $volatility > 0 ? round($weightedVolatility / $volatility, 2) : 1.0,
            'band' => self::band($volatility * 100),
            'toleranceStatus' => match (true) {
                $loss <= $tolerancePercent => 'within',
                $loss <= $tolerancePercent * 1.25 => 'close',
                default => 'above',
            },
            'flags' => self::flags($largestHolding, $growthShare * 100, $years),
        ];
    }

    private static function band(float $volatilityPercent): string
    {
        return match (true) {
            $volatilityPercent < 4 => 'Low',
            $volatilityPercent < 7 => 'Moderately low',
            $volatilityPercent < 11 => 'Moderate',
            $volatilityPercent < 15 => 'Moderately high',
            default => 'High',
        };
    }

    /**
     * @return list<string>
     */
    private static function flags(float $largestHolding, float $growthShare, int $years): array
    {
        $flags = [];

        if ($largestHolding >= 60) {
            $flags[] = 'Concentrated: more than 60% sits in a single asset class.';
        }

        if ($growthShare >= 80) {
            $flags[] = 'Growth-heavy: shares, property and alternatives make up 80% or more, and they tend to fall together in stress.';
        }

        if ($years <= 3 && $growthShare >= 50) {
            $flags[] = 'Short horizon: with three years or fewer, a large fall may not have time to recover.';
        }

        return $flags;
    }
}
