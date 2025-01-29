<?php

namespace App\Services;

use Illuminate\Support\HtmlString;

class DogGenome
{
    protected array $alleles;

    protected array $phenotype;

    public function __construct(?array $initialAlleles = null)
    {
        $this->alleles = $initialAlleles ?? $this->generateRandomAlleles();
        $this->calculatePhenotype();
    }

    protected function generateRandomAlleles(): array
    {
        return DogGenomeAlleleGenerator::generateRandomAlleles(config('genetics.inheritance_patterns'));
    }

    protected function calculatePhenotype(): void
    {
        $calculator = new DogPhenotypeCalculator($this->alleles);
        $this->phenotype = $calculator->calculatePhenotype();
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
        $alleles = DogGenomeAlleleGenerator::fromGenotype($genotype, config('genetics.inheritance_patterns'));

        return new self($alleles);
    }

    public function toHtmlString(): string
    {
        $alleleStrings = [];

        foreach ($this->alleles as $trait => $pair) {
            foreach ($pair as &$allele) {
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

        // combine the rest of $arr into a string
        $rest = implode($arr);

        return $first.($rest ? '<sup>'.$rest.'</sup>' : '');
    }
}
