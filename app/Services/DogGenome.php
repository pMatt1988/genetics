<?php

namespace App\Services;

class DogGenome
{
    protected array $alleles;
    protected array $phenotype;

    const INHERITANCE_PATTERNS = [
        'dominant_black' => [
            'solid_black' => ['dominant' => true, 'allele' => 'K'],
            'brindle' => ['intermediate' => true, 'allele' => 'kbr'],
            'allows_agouti' => ['dominant' => false, 'allele' => 'ky'],
        ],
        'agouti' => [
            'sable' => ['dominant' => true, 'allele' => 'Ay'],
            'wild' => ['dominant' => true, 'allele' => 'Aw'],
            'tan_points' => ['dominant' => false, 'allele' => 'at'],
            'recessive_black' => ['dominant' => false, 'allele' => 'a'],
        ],
        'base_color' => [
            'black' => ['dominant' => true, 'allele' => 'B'],
            'brown' => ['dominant' => false, 'allele' => 'b']
        ],
        'white_spotting' => [
            'solid' => ['dominant' => false, 'allele' => 's'],
            'pied' => ['dominant' => true, 'allele' => 'S'],
            'extreme_white' => ['dominant' => true, 'allele' => 'Sw']
        ],
        'dilution' => [
            'normal' => ['dominant' => true, 'allele' => 'D'],
            'dilute' => ['dominant' => false, 'allele' => 'd']
        ]
    ];

    public function __construct(array $initialAlleles = null)
    {
        $this->alleles = $initialAlleles ?? $this->generateRandomAlleles();
        $this->calculatePhenotype();
    }

    protected function generateRandomAlleles(): array
    {
        $alleles = [];
        foreach (self::INHERITANCE_PATTERNS as $trait => $patterns) {
            $allele1 = $this->getRandomAllele($patterns);
            $allele2 = $this->getRandomAllele($patterns);
            $alleles[$trait] = $this->orderAlleles($allele1, $allele2, $patterns);
        }
        return $alleles;
    }

    protected function getRandomAllele(array $patterns): string
    {
        $possible_alleles = array_column($patterns, 'allele');
        return $possible_alleles[array_rand($possible_alleles)];
    }

    protected function orderAlleles(string $allele1, string $allele2, array $patterns): array
    {
        // Get pattern info for each allele
        $getPatternInfo = function($allele) use ($patterns) {
            foreach ($patterns as $info) {
                if ($info['allele'] === $allele) return $info;
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
            array_filter($patterns, fn($info) => ($info['dominant'] ?? false) && !($info['intermediate'] ?? false)),
            'allele'
        );

        // Get intermediate dominant alleles
        $intermediate_alleles = array_column(
            array_filter($patterns, fn($info) => $info['intermediate'] ?? false),
            'allele'
        );

        // Helper function to get dominance level (2 for dominant, 1 for intermediate, 0 for recessive)
        $getDominanceLevel = function($allele) use ($dominant_alleles, $intermediate_alleles) {
            if (in_array($allele, $dominant_alleles)) return 2;
            if (in_array($allele, $intermediate_alleles)) return 1;
            return 0;
        };

        $level1 = $getDominanceLevel($allele1);
        $level2 = $getDominanceLevel($allele2);

        // If different dominance levels, higher dominance goes first
        if ($level1 !== $level2) {
            return $level1 > $level2 ? [$allele1, $allele2] : [$allele2, $allele1];
        }

        // If same dominance level, order by string value
        return $allele1 > $allele2 ? [$allele1, $allele2] : [$allele2, $allele1];
    }

    protected function calculatePhenotype(): void
    {
        $baseColor = $this->determineBaseColor();
        $diluted = $this->isDiluted();
        $pattern = $this->determinePattern();
        $isDominantBlack = $this->isDominantBlack();
        $agoutiPattern = $isDominantBlack ? 'dominant_black' : $this->determineAgoutiPattern();

        $finalColor = $diluted ? $this->getDilutedColor($baseColor) : $baseColor;

        $this->phenotype = [
            'base_color' => $baseColor,
            'pattern' => $pattern,
            'agouti' => $agoutiPattern,
            'dominant_black' => $isDominantBlack,
            'dilution' => $diluted ? 'dilute' : 'normal',
            'description' => $this->generateDescription($finalColor, $pattern, $agoutiPattern)
        ];
    }

    protected function determineAgoutiPattern(): string
    {
        $allele_pair = $this->alleles['agouti'];

        // Order of dominance: Ay > Aw > at > a
        if (in_array('Ay', $allele_pair)) return 'sable';
        if (in_array('Aw', $allele_pair)) return 'wild';
        if (in_array('at', $allele_pair)) return 'tan_points';
        return 'recessive_black';
    }

    protected function determineBaseColor(): string
    {
        $allele_pair = $this->alleles['base_color'];
        return (in_array('B', $allele_pair)) ? 'black' : 'brown';
    }

    protected function isDiluted(): bool
    {
        $allele_pair = $this->alleles['dilution'];
        return $allele_pair[0] === 'd' && $allele_pair[1] === 'd';
    }

    protected function getDilutedColor(string $baseColor): string
    {
        return match($baseColor) {
            'black' => 'blue',
            'brown' => 'isabella',
            default => $baseColor
        };
    }

    protected function determinePattern(): string
    {
        $allele_pair = $this->alleles['white_spotting'];
        sort($allele_pair);
        $genotype = implode('', $allele_pair);

        return match($genotype) {
            'SwSw' => 'mostly_white',
            'ss' => 'solid',
            default => 'pied'
        };
    }

    protected function isDominantBlack(): bool
    {
        $allele_pair = $this->alleles['dominant_black'];
        return in_array('K', $allele_pair);
    }

    protected function determineKPattern(): string
    {
        $allele_pair = $this->alleles['dominant_black'];

        // Sort to handle cases like kbrK (should be Kkbr)
        sort($allele_pair);
        $genotype = implode('', $allele_pair);

        // K is dominant over all
        if (in_array('K', $allele_pair)) {
            return 'dominant_black';
        }

        // kbr is dominant over ky
        if (in_array('kbr', $allele_pair)) {
            return 'brindle';
        }

        // kyky allows A locus expression
        return 'allows_agouti';
    }

    protected function generateDescription(string $color, string $pattern, string $agoutiPattern): string
    {
        $kPattern = $this->determineKPattern();

        // First determine base appearance based on K locus
        $baseDescription = match($kPattern) {
            'dominant_black' => $color,
            'brindle' => match($agoutiPattern) {
                'sable' => 'Brindle sable',
                'wild' => 'Brindle wolf-like pattern',
                'tan_points' => 'Brindle with tan points',
                'recessive_black' => 'Brindle',
                default => 'Brindle'
            },
            'allows_agouti' => match($agoutiPattern) {
                'sable' => 'Sable with ' . $color . ' overlay',
                'wild' => 'Wild type (wolf-like)',
                'tan_points' => $color . ' with tan points',
                'recessive_black' => $color,
                default => $color
            }
        };

        if ($pattern === 'solid') {
            return $baseDescription;
        } elseif ($pattern === 'mostly_white') {
            return "White with $baseDescription markings";
        } else {
            return "$baseDescription with white patches";
        }
    }

    public function getAlleles(): array
    {
        return $this->alleles;
    }

    public function getPhenotype(): array
    {
        return $this->phenotype;
    }
}
