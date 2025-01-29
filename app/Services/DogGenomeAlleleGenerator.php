<?php

namespace App\Services;

class DogGenomeAlleleGenerator
{
    public static function generateRandomAlleles(array $inheritancePatterns): array
    {
        $alleles = [];
        foreach ($inheritancePatterns as $trait => $patterns) {
            $allele1 = self::getRandomAllele($patterns);
            $allele2 = self::getRandomAllele($patterns);
            $alleles[$trait] = self::orderAlleles($allele1, $allele2, $patterns);
        }

        return $alleles;
    }

    protected static function getRandomAllele(array $patterns): string
    {
        $possible_alleles = array_column($patterns, 'allele');

        return $possible_alleles[array_rand($possible_alleles)];
    }

    public static function orderAlleles(string $allele1, string $allele2, array $patterns): array
    {
        // Get pattern info for each allele
        $getPatternInfo = function ($allele) use ($patterns) {
            foreach ($patterns as $info) {
                if ($info['allele'] === $allele) {
                    return $info;
                }
            }

            return null;
        };

        $info1 = $getPatternInfo($allele1);
        $info2 = $getPatternInfo($allele2);

        // First check priority if it exists
        if (isset($info1['priority']) && isset($info2['priority'])) {
            if ($info1['priority'] !== $info2['priority']) {
                return $info1['priority'] > $info2['priority'] ?
                    [$allele1, $allele2] :
                    [$allele2, $allele1];
            }
        }

        // Get fully dominant alleles
        $dominant_alleles = array_column(
            array_filter($patterns, fn ($info) => ($info['dominant'] ?? false) && ! ($info['intermediate'] ?? false)),
            'allele'
        );

        // Get intermediate dominant alleles
        $intermediate_alleles = array_column(
            array_filter($patterns, fn ($info) => $info['intermediate'] ?? false),
            'allele'
        );

        // Helper function to get dominance level (2 for dominant, 1 for intermediate, 0 for recessive)
        $getDominanceLevel = function ($allele) use ($dominant_alleles, $intermediate_alleles) {
            if (in_array($allele, $dominant_alleles)) {
                return 2;
            }
            if (in_array($allele, $intermediate_alleles)) {
                return 1;
            }

            return 0;
        };

        $level1 = $getDominanceLevel($allele1);
        $level2 = $getDominanceLevel($allele2);

        // If different dominance levels, higher dominance goes first
        if ($level1 !== $level2) {
            return $level1 > $level2 ?
                [$allele1, $allele2] :
                [$allele2, $allele1];
        }

        // If same dominance level, order by string value
        return $allele1 > $allele2 ?
            [$allele1, $allele2] :
            [$allele2, $allele1];
    }

    public static function fromGenotype(string $genotype, array $inheritancePatterns): array
    {
        // Split into allele pairs
        $pairs = explode(' ', trim($genotype));

        if (count($pairs) !== 6) {
            throw new \InvalidArgumentException(
                'Genotype must contain exactly 6 space-separated allele pairs'
            );
        }

        // Map positions to traits
        $traitMap = [
            0 => 'extension',
            1 => 'dominant_black',
            2 => 'agouti',
            3 => 'base_color',
            4 => 'white_spotting',
            5 => 'dilution',
        ];

        $alleles = [];
        foreach ($pairs as $index => $pair) {
            $trait = $traitMap[$index];

            // Each pair should be exactly 2 characters for single-letter alleles
            // or 4-6 characters for multi-letter alleles (e.g., 'kbr')
            if (strlen($pair) < 2) {
                throw new \InvalidArgumentException(
                    "Invalid allele pair format for {$trait}: {$pair}"
                );
            }

            // Get valid alleles for this trait
            $validAlleles = array_column($inheritancePatterns[$trait], 'allele');

            // Split the pair into individual alleles
            // Handle both single-letter (e.g., 'Bb') and multi-letter (e.g., 'kbrkbr') alleles
            $allele1 = self::extractFirstAllele($pair, $validAlleles);
            $allele2 = self::extractSecondAllele($pair, $validAlleles);

            // Validate both alleles
            if (! in_array($allele1, $validAlleles) || ! in_array($allele2, $validAlleles)) {
                throw new \InvalidArgumentException(
                    "Invalid allele(s) for {$trait}: {$allele1}, {$allele2}. ".
                    'Valid alleles are: '.implode(', ', $validAlleles)
                );
            }

            $alleles[$trait] = self::orderAlleles($allele1, $allele2, $inheritancePatterns[$trait]);
        }

        return $alleles;
    }

    private static function extractFirstAllele(string $pair, array $validAlleles): string
    {
        foreach ($validAlleles as $validAllele) {
            if (str_starts_with($pair, $validAllele)) {
                return $validAllele;
            }
        }
        throw new \InvalidArgumentException("Could not extract valid first allele from: {$pair}");
    }

    private static function extractSecondAllele(string $pair, array $validAlleles): string
    {
        foreach ($validAlleles as $validAllele) {
            if (str_ends_with($pair, $validAllele)) {
                return $validAllele;
            }
        }
        throw new \InvalidArgumentException("Could not extract valid second allele from: {$pair}");
    }
}
