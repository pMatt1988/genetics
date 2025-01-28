<?php

namespace App\Models;

use App\Services\DogGenome;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{

    use HasFactory;
    protected $fillable = [
        'name',
        'breed',
        'genome_data',
        'color_description'
    ];

    protected $casts = [
        'genome_data' => 'array',
    ];


    public function uniqueIds()
    {
        return ['id'];
    }

    protected DogGenome $genome;

    public function initializeGenome(): void
    {
        $this->genome = new DogGenome($this->genome_data);
        $this->color_description = $this->genome->getPhenotype()['description'];
    }

    public function breed(Dog $partner): Dog
    {
        $offspring_genome = $this->genome->breed($partner->genome);
        $offspring_phenotype = $offspring_genome->getPhenotype();

        return static::create([
            'name' => 'Puppy',
            'breed' => $this->determineOffspringBreed($partner),
            'genome_data' => $offspring_genome->getAlleles(),
            'color_description' => $offspring_phenotype['description']
        ]);
    }

    protected function determineOffspringBreed(Dog $partner): string
    {
        if ($this->breed === $partner->breed) {
            return $this->breed;
        }

        return 'Mixed (' . $this->breed . ' x ' . $partner->breed . ')';
    }
}
