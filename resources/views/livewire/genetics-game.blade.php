<div class="max-w-2xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-2xl font-bold mb-3">Dog Genetics Game</h2>
            <p class="text-gray-600">Score: {{ $score }}/{{ $attempts }}</p>
        </div>

        <!-- Alleles -->
        <div class="mb-12">
            <div class="font-mono text-8xl text-center tracking-widest">{{ $alleleString }}</div>
        </div>

        <div class="mb-6 text-center">
            <p>Please pick the answer that best describes the phenotype of the above genotype.</p>
        </div>

        <form wire:submit.prevent="checkAnswer">
            <!-- Choice grid -->
            <div class="space-y-3">
                @foreach($choices as $choice)
                    <label class="block p-4 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors {{ $submitted ? ($choice === $currentPhenotype['description'] ? 'bg-green-50 border-green-500' : ($choice === $selectedChoice ? 'bg-red-50 border-red-500' : '')) : '' }}">
                        <div class="flex items-center">
                            <input
                                type="radio"
                                name="phenotype"
                                value="{{ $choice }}"
                                wire:model.live="selectedChoice"
                                class="h-5 w-5 text-blue-600"
                                {{ $submitted ? 'disabled' : '' }}
                            >
                            <span class="ml-3 text-xl font-normal">{{ $choice }}</span>
                        </div>
                    </label>
                @endforeach
            </div>

            <!-- Feedback -->
            @if($feedback)
                <div class="mt-8 p-4 rounded-lg {{ str_contains($feedback, 'Correct') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $feedback }}
                </div>
            @endif

            <!-- Buttons -->
            <div class="mt-8 flex justify-end">
                @if(!$submitted)
                    <button
                        type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 transition-colors"
                        {{ !$selectedChoice ? 'disabled' : '' }}
                    >
                        Check Answer
                    </button>
                @else
                    <button
                        type="button"
                        wire:click="generateNewProblem"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors"
                    >
                        Next Problem
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>
