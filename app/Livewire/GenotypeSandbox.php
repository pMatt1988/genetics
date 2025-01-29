<?php

namespace App\Livewire;

use App\Services\DogGenome;
use Livewire\Component;

class GenotypeSandbox extends Component
{

    public string $description = '';
    public string $eLocusString = '';
    public string $kLocusString = '';
    public string $aLocusString = '';
    public string $bLocusString = '';
    public string $sLocusString = '';
    public string $dLocusString = '';

    public function mount() {
        $dogGenome = new DogGenome();
        $alleles = $dogGenome->getAlleles();

        $this->eLocusString = $alleles['extension'][0] . $alleles['extension'][1];
        $this->kLocusString = $alleles['dominant_black'][0] . $alleles['dominant_black'][1];
        $this->aLocusString = $alleles['agouti'][0] . $alleles['agouti'][1];
        $this->bLocusString = $alleles['base_color'][0] . $alleles['base_color'][1];
        $this->sLocusString = $alleles['white_spotting'][0] . $alleles['white_spotting'][1];
        $this->dLocusString = $alleles['dilution'][0] . $alleles['dilution'][1];


        $this->description = $dogGenome->getPhenotype()['description'];
    }
    public function updateGenotypeDescription(): void {
        $genotypeString = sprintf("%s %s %s %s %s %s",
            $this->eLocusString,
            $this->kLocusString,
            $this->aLocusString,
            $this->bLocusString,
            $this->sLocusString,
            $this->dLocusString
        );
        $this->description = DogGenome::fromGenotype($genotypeString)->getPhenotype()['description'];
    }

    public function render()
    {
        return view('livewire.genotype-sandbox');
    }
}
