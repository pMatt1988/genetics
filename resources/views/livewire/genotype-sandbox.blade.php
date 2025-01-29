<div class="max-w-5xl mx-auto p-6">

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

            <div class="flex justify-end">
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


</div>
