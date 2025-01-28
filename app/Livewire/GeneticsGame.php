<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\DogGenome;

class GeneticsGame extends Component
{
    public string $alleleString = '';
    public array $currentPhenotype = [];
    public array $choices = [];
    public ?string $selectedChoice = null;
    public int $score = 0;
    public int $attempts = 0;
    public bool $submitted = false;
    public string $feedback = '';

    public function mount()
    {
        $this->generateNewProblem();
    }

    public function generateNewProblem()
    {
        $genome = new DogGenome();
        $alleles = $genome->getAlleles();

        // Format alleles with K locus first, then A locus, followed by others
        $this->alleleString = sprintf("%s%s %s%s %s%s %s%s %s%s",
            $alleles['dominant_black'][0],
            $alleles['dominant_black'][1],
            $alleles['agouti'][0],
            $alleles['agouti'][1],
            $alleles['base_color'][0],
            $alleles['base_color'][1],
            $alleles['white_spotting'][0],
            $alleles['white_spotting'][1],
            $alleles['dilution'][0],
            $alleles['dilution'][1]
        );

        $this->currentPhenotype = $genome->getPhenotype();
        $this->choices = $this->generateChoices($this->currentPhenotype['description']);
        $this->selectedChoice = null;
        $this->submitted = false;
        $this->feedback = '';

        // Debugging - let's see what alleles we're getting
        // dd($alleles, $this->alleleString);
    }

    protected function generateChoices(string $correctAnswer): array
    {
        // Base patterns we can use
        $patterns = [
            'Sable with {color} overlay',
            'White with {color} markings',
            '{color} with tan points',
            'Wild type with white patches',
            'Solid {color}',
            '{color} with white patches',
            'Brindle with white patches',
            'Brindle with tan points',
            'White with brindle markings',
            'Solid brindle'
        ];

        $colors = ['Black', 'Brown', 'Blue', 'Isabella'];

        // Always include the correct answer
        $choices = [$correctAnswer];

        // Generate wrong answers until we have 4 unique choices
        while (count($choices) < 4) {
            // Pick a random pattern and color
            $pattern = $patterns[array_rand($patterns)];
            $color = $colors[array_rand($colors)];

            $wrongAnswer = str_replace('{color}', $color, $pattern);

            // Only add if it's not the correct answer and not already in choices
            if ($wrongAnswer !== $correctAnswer && !in_array($wrongAnswer, $choices)) {
                $choices[] = $wrongAnswer;
            }
        }

        shuffle($choices);
        return $choices;
    }

    public function checkAnswer()
    {
        if (!$this->selectedChoice) {
            return;
        }

        $this->attempts++;
        $this->submitted = true;

        $isCorrect = $this->selectedChoice === $this->currentPhenotype['description'];

        if ($isCorrect) {
            $this->score++;
            $this->feedback = 'Correct!';
        } else {
            $this->feedback = 'Not quite. The correct answer is: ' . $this->currentPhenotype['description'];
        }
    }

    public function render()
    {
        return view('livewire.genetics-game');
    }
}
