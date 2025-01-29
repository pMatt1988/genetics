@extends('layout')


@section('content')

    <livewire:genotype-sandbox/>
    <div class="max-w-5xl mx-auto p-6 rounded-lg mt-6">
        <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">

            <h2 class="text-2xl mb-3"><strong>Legend</strong></h2>

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
                    <p><strong>A<sup>w</sup></strong> - Wild type</p>
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
@endsection
