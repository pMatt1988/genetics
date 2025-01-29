<div class="max-w-5xl mx-auto p-6" x-data="{ showLegend: false }">
    <!-- resources/views/livewire/genotype-sandbox.blade.php -->
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <div class="text-center mb-12">
            <h2 class="text-2xl font-bold mb-3">Dog Genetics Game</h2>
        </div>

        <form wire:submit.prevent="updateGenotypeDescription" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- E Locus -->
                <div class="space-y-2">
                    <label for="eLocus" class="block text-sm font-medium text-gray-700">
                        E Locus
                    </label>
                    <input
                        type="text"
                        id="eLocus"
                        wire:model="eLocusString"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                </div>

                <!-- K Locus -->
                <div class="space-y-2">
                    <label for="kLocus" class="block text-sm font-medium text-gray-700">
                        K Locus
                    </label>
                    <input
                        type="text"
                        id="kLocus"
                        wire:model="kLocusString"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                </div>

                <!-- A Locus -->
                <div class="space-y-2">
                    <label for="aLocus" class="block text-sm font-medium text-gray-700">
                        A Locus
                    </label>
                    <input
                        type="text"
                        id="aLocus"
                        wire:model="aLocusString"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                </div>

                <!-- B Locus -->
                <div class="space-y-2">
                    <label for="bLocus" class="block text-sm font-medium text-gray-700">
                        B Locus
                    </label>
                    <input
                        type="text"
                        id="bLocus"
                        wire:model="bLocusString"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                </div>

                <!-- S Locus -->
                <div class="space-y-2">
                    <label for="sLocus" class="block text-sm font-medium text-gray-700">
                        S Locus
                    </label>
                    <input
                        type="text"
                        id="sLocus"
                        wire:model="sLocusString"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                </div>

                <!-- D Locus -->
                <div class="space-y-2">
                    <label for="dLocus" class="block text-sm font-medium text-gray-700">
                        D Locus
                    </label>
                    <input
                        type="text"
                        id="dLocus"
                        wire:model="dLocusString"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                </div>
            </div>

            <div class="flex justify-between items-center">
                <button
                    type="button"
                    @click="showLegend = true"
                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium"
                >
                    View Genetics Legend
                </button>

                <button
                    type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Submit
                </button>
            </div>
        </form>
        @if (isset($description))
            <div class="mt-8 p-4 bg-gray-50 rounded-md">
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Genotype Description:
                </h3>
                <p class="text-gray-700">
                    {{ $description }}
                </p>
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div
        x-show="showLegend"
        class="relative z-50"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <!-- Background backdrop -->
        <div
            x-show="showLegend"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            @click="showLegend = false"
        ></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    x-show="showLegend"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
                    @click.away="showLegend = false"
                >
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modal-title">
                            Genetics Legend
                        </h3>
                        <button
                            type="button"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-500"
                            @click="showLegend = false"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="mt-2 space-y-4">
                            <!-- Your existing legend content will go here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

