<?php

namespace App\Services;

class DogPhenotypeCalculator
{
    protected array $alleles;

    public function __construct(array $alleles)
    {
        $this->alleles = $alleles;
    }

    public function calculatePhenotype(): array
    {
        $baseColor = $this->determineBaseColor();
        $diluted = $this->isDiluted();
        $pattern = $this->determinePattern();
        $extension = $this->determineExtension();
        $isDominantBlack = $this->isDominantBlack();
        $agoutiPattern = $isDominantBlack ? 'dominant_black' : $this->determineAgoutiPattern();

        $finalColor = $diluted ? $this->getDilutedColor($baseColor) : $baseColor;

        return [
            'base_color' => $baseColor,
            'pattern' => $pattern,
            'agouti' => $agoutiPattern,
            'dominant_black' => $isDominantBlack,
            'extension' => $extension,
            'dilution' => $diluted ? 'dilute' : 'normal',
            'description' => $this->generateDescription($finalColor, $pattern, $agoutiPattern, $extension),
        ];
    }

    protected function determineAgoutiPattern(): string
    {
        $allele_pair = $this->alleles['agouti'];

        // Order of dominance: Ay > Aw > at > a
        if (in_array('Ay', $allele_pair)) {
            return 'sable';
        }
        if (in_array('Aw', $allele_pair)) {
            return 'wild';
        }
        if (in_array('at', $allele_pair)) {
            return 'tan_points';
        }

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

        if (in_array('E', $allele_pair)) {
            return 'normal';
        }

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
}
