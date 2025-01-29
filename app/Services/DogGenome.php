<?php

namespace App\Services;

use Illuminate\Support\HtmlString;

class DogGenome
{
    protected array $alleles;
    protected array $phenotype;

    const INHERITANCE_PATTERNS = [
        'extension' => [
            'normal' => ['dominant' => true,
                'allele' => 'E'],
            'recessive_red' => ['dominant' => false,
                'allele' => 'e']
        ],
        'dominant_black' => [
            'solid_black' => ['dominant' => true,
                'allele' => 'K'],
            'brindle' => ['intermediate' => true,
                'allele' => 'kbr'],
            'recessive_non_black' => ['dominant' => false,
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
            'liver' => ['dominant' => false,
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
            $alleles[$trait] = static::orderAlleles($allele1, $allele2, $patterns);
        }
        return $alleles;
    }

    protected function getRandomAllele(array $patterns): string
    {
        $possible_alleles = array_column($patterns, 'allele');
        return $possible_alleles[array_rand($possible_alleles)];
    }

    protected static function orderAlleles(string $allele1, string $allele2, array $patterns): array
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
        $getDominanceLevel = function ($allele) use ($dominant_alleles, $intermediate_alleles) {
            if (in_array($allele, $dominant_alleles)) return 2;
            if (in_array($allele, $intermediate_alleles)) return 1;
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

    protected function calculatePhenotype(): void
    {
        $baseColor = $this->determineBaseColor();
        $diluted = $this->isDiluted();
        $pattern = $this->determinePattern();
        $extension = $this->determineExtension();
        $isDominantBlack = $this->isDominantBlack();
        $agoutiPattern = $isDominantBlack ? 'dominant_black' : $this->determineAgoutiPattern();

        $finalColor = $diluted ? $this->getDilutedColor($baseColor) : $baseColor;

        $this->phenotype = [
            'base_color' => $baseColor,
            'pattern' => $pattern,
            'agouti' => $agoutiPattern,
            'dominant_black' => $isDominantBlack,
            'extension' => $extension,
            'dilution' => $diluted ? 'dilute' : 'normal',
            'description' => $this->generateDescription($finalColor, $pattern, $agoutiPattern, $extension)
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
        return (in_array('B', $allele_pair)) ? 'black' : 'liver';
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
            'liver' => 'isabella',
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
        return 'recessive_non_black';
    }

    protected function determineExtension(): string
    {
        $allele_pair = $this->alleles['extension'];

        if (in_array('E', $allele_pair)) return 'normal';
        return 'recessive_red';
    }

    protected function generateDescription(string $color, string $pattern, string $agoutiPattern, string $extension): string
    {

        // Handle recessive red first as it's epistatic to most other loci
        if ($extension === 'recessive_red') {
            $baseDescription = match (strtolower($color)) {
                'blue' => 'Dilute Red',
                'liver' => 'Liver Red',
                'isabella' => 'Isabella Red',
                default => 'Red'
            };
        } else {
            $kPattern = $this->determineKPattern();



            // First determine base appearance based on K locus
            $baseDescription = match ($kPattern) {
                'dominant_black' => $color,
                'brindle' => match ($agoutiPattern) {
                    'wild' => "$color brindle with agouti",
                    'tan_points' => "$color with brindle points",
                    'recessive_black' => "$color",
                    default => "$color brindle"
                },
                'recessive_non_black' => match ($agoutiPattern) {
                    'sable' => "Sable with $color overlay",
                    'wild' => "$color with agouti",
                    'tan_points' => "$color with tan points",
                    default => $color
                }
            };
        }

        // Then add white pattern modifications
        return ucfirst(match ($pattern) {
            default => $baseDescription,
            'pied' => "Pied $baseDescription",
            'minimal_white' => "$baseDescription and minimal white",
        });
    }

    public function getAlleles(): array
    {
        return $this->alleles;
    }

    public function getPhenotype(): array
    {
        return $this->phenotype;
    }

    public static function fromGenotype(string $genotype): self
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
            5 => 'dilution'
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


            $alleles[$trait] = static::orderAlleles($allele1, $allele2, self::INHERITANCE_PATTERNS[$trait]);
        }

        return new self($alleles);
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

    public function toHtmlString(): string
    {
        $alleleStrings = [];

        foreach($this->alleles as $trait => $pair) {
            foreach($pair as &$allele) {
                $allele = $this->addSupToAllele($allele);
            }
            $alleleStrings[] = implode('/', $pair);
        }

        return new HtmlString(implode(' ', $alleleStrings));
    }

    private function addSupToAllele($allele): string
    {
        $arr = str_split($allele);
        $first = array_shift($arr);

        //combine the rest of $arr into a string
        $rest = implode($arr);

        return $first . ($rest ? '<sup>' . $rest . '</sup>': '');
    }

}
