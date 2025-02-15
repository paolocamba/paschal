<?php

declare(strict_types=1);

namespace Phpml\Preprocessing;

use Phpml\Exception\NormalizerException;
use Phpml\Math\Statistic\Mean;
use Phpml\Math\Statistic\StandardDeviation;

class Normalizer implements Preprocessor
{
    public const NORM_L1 = 1;
    public const NORM_L2 = 2;
    public const NORM_STD = 3;

    private int $norm;
    private bool $fitted = false;
    private array $std = [];
    private array $mean = [];

    public function __construct(int $norm = self::NORM_L2)
    {
        if (!in_array($norm, [self::NORM_L1, self::NORM_L2, self::NORM_STD], true)) {
            throw new NormalizerException('Unknown norm supplied.');
        }

        $this->norm = $norm;
    }

    public function fit(array $samples, ?array $targets = null): void
    {
        if ($this->fitted) {
            return;
        }

        if (empty($samples)) {
            throw new NormalizerException('Samples array cannot be empty.');
        }

        if ($this->norm === self::NORM_STD) {
            $features = range(0, count($samples[0]) - 1);
            foreach ($features as $i) {
                $values = array_column($samples, $i);
                $this->std[$i] = StandardDeviation::population($values);
                $this->mean[$i] = Mean::arithmetic($values);
            }
        }

        $this->fitted = true;
    }

    public function transform(array &$samples, ?array &$targets = null): void
    {
        if (empty($samples)) {
            return;
        }

        $methods = [
            self::NORM_L1 => 'normalizeL1',
            self::NORM_L2 => 'normalizeL2',
            self::NORM_STD => 'normalizeSTD',
        ];
        $method = $methods[$this->norm];

        $this->fit($samples);

        foreach ($samples as &$sample) {
            $this->{$method}($sample);
        }
    }

    private function normalizeL1(array &$sample): void
    {
        $norm1 = array_sum(array_map('abs', $sample));

        if ($norm1 == 0) {
            $count = count($sample);
            $sample = array_fill(0, $count, 1.0 / $count);
        } else {
            array_walk($sample, function (&$feature) use ($norm1): void {
                $feature /= $norm1;
            });
        }
    }

    private function normalizeL2(array &$sample): void
    {
        $norm2 = sqrt(array_sum(array_map(fn($f) => $f * $f, $sample)));

        if ($norm2 == 0) {
            $sample = array_fill(0, count($sample), 1);
        } else {
            array_walk($sample, function (&$feature) use ($norm2): void {
                $feature /= $norm2;
            });
        }
    }

    private function normalizeSTD(array &$sample): void
    {
        foreach (array_keys($sample) as $i) {
            if ($this->std[$i] != 0) {
                $sample[$i] = ($sample[$i] - $this->mean[$i]) / $this->std[$i];
            } else {
                $sample[$i] = 0;
            }
        }
    }
}
