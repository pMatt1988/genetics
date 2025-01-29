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

        // Format alleles with E locus first, then K locus, then others
        $this->alleleString = $genome->toHtmlString();

        $this->currentPhenotype = $genome->getPhenotype();
        $this->choices = $this->generateChoices($this->currentPhenotype['description']);
        $this->selectedChoice = null;
        $this->submitted = false;
        $this->feedback = '';
    }

    protected function generateChoices(string $correctAnswer): array
    {
        // Always include the correct answer
        $choices = [$correctAnswer];

        // Generate wrong answers until we have 4 unique choices
        while (count($choices) < 4) {
            // Pick a random pattern and color
            $dogGenome = new DogGenome();

            $wrongAnswer = $dogGenome->getPhenotype()['description'];

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
