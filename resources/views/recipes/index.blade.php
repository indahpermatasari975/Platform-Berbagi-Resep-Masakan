@extends('layouts.app')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            🍳 Daftar Resep
        </h2>

        <p class="text-muted mb-0">
            Temukan resep nusantara dan internasional favoritmu
        </p>
    </div>

    <a href="{{ route('recipes.create') }}"
        class="btn btn-warning mt-3 mt-md-0">

        + Tambah Resep

    </a>

</div>

<!-- SEARCH -->

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <form method="GET"
            action="{{ route('recipes.index') }}">

            <div class="input-group input-group-lg">

                <span class="input-group-text bg-white border-0">
                    🔎
                </span>

                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    class="form-control"
                    placeholder="Cari resep, kategori, atau bahan...">

                <button
                    class="btn btn-warning fw-semibold">

                    Cari

                </button>

            </div>

        </form>

    </div>

</div>

<!-- INFO -->

<div class="mb-4">

    <span class="badge bg-warning text-dark">

        Total Resep:
        {{ $recipes->total() }}

    </span>

</div>

<!-- LIST RESEP -->

@if($recipes->count())

<div class="row g-4">

    @foreach($recipes as $recipe)

        @include(
            'recipes.partials.recipe-card',
            ['recipe' => $recipe]
        )

    @endforeach

</div>

@else

<div class="card border-0 shadow-sm">

    <div class="card-body text-center py-5">

        <h4>
            😢 Resep Tidak Ditemukan
        </h4>

        <p class="text-muted">

            Tidak ada resep yang sesuai dengan pencarian Anda.

        </p>

        <a
            href="{{ route('recipes.index') }}"
            class="btn btn-warning">

            Tampilkan Semua Resep

        </a>

    </div>

</div>

@endif

<!-- PAGINATION -->

<div class="mt-5 d-flex justify-content-center">

    {{ $recipes->withQueryString()->links() }}

</div>

@endsection
