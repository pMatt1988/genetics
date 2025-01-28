<?php

namespace App\Services;

class DogGenome
{
    protected array $alleles;
    protected array $phenotype;

    const INHERITANCE_PATTERNS = [
        'dominant_black' => [
            'solid_black' => ['dominant' => true,
                'allele' => 'K'],
            'brindle' => ['intermediate' => true,
                'allele' => 'kbr'],
            'allows_agouti' => ['dominant' => false,
                'allele' => 'ky'],
        ],
        'agouti' => [
            'sable' => ['dominant' => true,
                'allele' => 'Ay'],
            'wild' => ['dominant' => true,
                'allele' => 'Aw'],
            'tan_points' => ['dominant' => false,
                'allele' => 'at'],
            'recessive_black' => ['dominant' => false,
                'allele' => 'a'],
        ],
        'base_color' => [
            'black' => ['dominant' => true,
                'allele' => 'B'],
            'brown' => ['dominant' => false,
                'allele' => 'b']
        ],
        'white_spotting' => [
            'solid' => ['dominant' => true,
                'allele' => 'S'],
            'pied' => ['dominant' => false,
                'allele' => 'sp']
        ],
        'dilution' => [
            'normal' => ['dominant' => true,
                'allele' => 'D'],
            'dilute' => ['dominant' => false,
                'allele' => 'd']
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
        $getPatternInfo = function ($allele) use ($patterns) {
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
                    [$allele1,
                        $allele2] :
                    [$allele2,
                        $allele1];
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
        $getDominanceLevel = function ($allele) use ($dominant_alleles, $intermediate_alleles) {
            if (in_array($allele, $dominant_alleles)) return 2;
            if (in_array($allele, $intermediate_alleles)) return 1;
            return 0;
        };

        $level1 = $getDominanceLevel($allele1);
        $level2 = $getDominanceLevel($allele2);

        // If different dominance levels, higher dominance goes first
        if ($level1 !== $level2) {
            return $level1 > $level2 ? [$allele1,
                $allele2] : [$allele2,
                $allele1];
        }

        // If same dominance level, order by string value
        return $allele1 > $allele2 ? [$allele1,
            $allele2] : [$allele2,
            $allele1];
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
        return match ($baseColor) {
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

        return match ($genotype) {
            'SS' => 'solid',
            'Ssp' => 'minimal_white',
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

        $color = ucfirst($color);

        // First determine base appearance based on K locus
        $baseDescription = match ($kPattern) {
            'dominant_black' => $color,
            'brindle' => match ($agoutiPattern) {
                'wild' => "$color brindle wolf-like pattern",
                'tan_points' => "$color with brindle points",
                'recessive_black' => "$color",
                default => "$color brindle"
            },
            'allows_agouti' => match ($agoutiPattern) {
                'sable' => "Sable with $color overlay",
                'wild' => match ($color) {
                    'blue' => 'Blue-gray wolf-like pattern',
                    'isabella' => 'Isabella wolf-like pattern',
                    default => 'Wild type (wolf-like)'
                },
                'tan_points' => "$color with tan points",
//                'recessive_black' => $color,
                default => $color
            }
        };

        // Then add white pattern modifications

        return match ($pattern) {
            'solid' => $baseDescription,
            'minimal_white' => "$baseDescription with minimal white",
            default => "$baseDescription with white patches"
        };
    }

    public function getAlleles(): array
    {
        return $this->alleles;
    }

    public function getPhenotype(): array
    {
        return $this->phenotype;
    }


    /**
     * Creates a DogGenome instance from a genotype string.
     * Format: "K1K2 A1A2 B1B2 S1S2 D1D2" where each pair represents alleles for:
     * - K locus (dominant black)
     * - A locus (agouti)
     * - B locus (base color)
     * - S locus (white spotting)
     * - D locus (dilution)
     *
     * @param string $genotype Space-separated allele pairs
     * @return self
     * @throws \InvalidArgumentException
     */
    public static function fromGenotype(string $genotype): self
    {
        // Split into allele pairs
        $pairs = explode(' ', trim($genotype));

        if (count($pairs) !== 5) {
            throw new \InvalidArgumentException(
                'Genotype must contain exactly 5 space-separated allele pairs'
            );
        }

        // Map positions to traits
        $traitMap = [
            0 => 'dominant_black',
            1 => 'agouti',
            2 => 'base_color',
            3 => 'white_spotting',
            4 => 'dilution'
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
            $validAlleles = array_column(self::INHERITANCE_PATTERNS[$trait], 'allele');

            // Split the pair into individual alleles
            // Handle both single-letter (e.g., 'Bb') and multi-letter (e.g., 'kbrkbr') alleles
            $allele1 = self::extractFirstAllele($pair, $validAlleles);
            $allele2 = self::extractSecondAllele($pair, $validAlleles);

            // Validate both alleles
            if (!in_array($allele1, $validAlleles) || !in_array($allele2, $validAlleles)) {
                throw new \InvalidArgumentException(
                    "Invalid allele(s) for {$trait}: {$allele1}, {$allele2}. " .
                    "Valid alleles are: " . implode(', ', $validAlleles)
                );
            }

            // Order the alleles based on dominance by creating a temporary instance
            $temp = new self();
            $alleles[$trait] = $temp->orderAlleles($allele1, $allele2, self::INHERITANCE_PATTERNS[$trait]);
        }

        return new self($alleles);
    }

    /**
     * Extracts the first allele from a pair string
     */
    private static function extractFirstAllele(string $pair, array $validAlleles): string
    {
        foreach ($validAlleles as $validAllele) {
            if (str_starts_with($pair, $validAllele)) {
                return $validAllele;
            }
        }
        throw new \InvalidArgumentException("Could not extract valid first allele from: {$pair}");
    }

    /**
     * Extracts the second allele from a pair string
     */
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
