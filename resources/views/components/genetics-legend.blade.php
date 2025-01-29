@props(['trigger'])

<!-- resources/views/components/genetics-legend.blade.php -->
<div x-data="{ showLegend: false }">
    <!-- Trigger Button -->
    <button type="button" @click="showLegend = true"
        class="m-4 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 transition-colors">
        {{ $trigger ?? 'View Legend' }}
    </button>

    <!-- Modal wrapper -->
    <div x-cloak>
        <!-- Modal -->
        <div x-show="showLegend" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Background backdrop -->
            <div x-show="showLegend" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showLegend = false"></div>

            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="showLegend" x-cloak x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
                        @click.away="showLegend = false">
                        <div>
                            <h2 class="text-2xl mb-3"><strong>Legend</strong></h2>
                            <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500"
                                @click="showLegend = false">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <div>
                                <h3 class="text-xl"><strong>E Locus</strong></h3>
                                <div class="pl-3">
                                    <p><strong>E</strong> - Normal</p>
                                    <p><strong>e</strong> - Recessive Red</p>
                                </div>

                                <h3 class="text-xl mt-4"><strong>K Locus</strong></h3>
                                <div class="pl-3">
                                    <p><strong>K</strong> - Dominant Black</p>
                                    <p><strong>k<sup>br</sup></strong> - Brindle</p>
                                    <p><strong>k<sup>y</sup></strong> - Recessive Non-Black</p>
                                </div>

                                <h3 class="text-xl mt-4"><strong>A Locus</strong></h3>
                                <div class="pl-3">
                                    <p><strong>A<sup>y</sup></strong> - Sable</p>
                                    <p><strong>A<sup>w</sup></strong> - Agouti</p>
                                    <p><strong>a<sup>t</sup></strong> - Tan points</p>
                                    <p><strong>a</strong> - Recessive Black</p>
                                </div>

                                <h3 class="text-xl mt-4"><strong>B Locus</strong></h3>
                                <div class="pl-3">
                                    <p><strong>B</strong> - Black</p>
                                    <p><strong>b</strong> - Brown</p>
                                </div>

                                <h3 class="text-xl mt-4"><strong>S Locus</strong></h3>
                                <div class="pl-3">
                                    <p><strong>S</strong> - Solid</p>
                                    <p><strong>s<sup>p</sup></strong> - Pied</p>
                                </div>

                                <h3 class="text-xl mt-4"><strong>D Locus</strong></h3>
                                <div class="pl-3">
                                    <p><strong>D</strong> - Normal</p>
                                    <p><strong>d</strong> - Dilute</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
