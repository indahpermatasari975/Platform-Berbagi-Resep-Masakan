@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
        <div>
            <h2 class="mb-1">Favorit Saya</h2>
            <div class="text-muted small">Resep yang kamu simpan</div>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($recipes as $recipe)
            @include('recipes.partials.recipe-card', ['recipe' => $recipe])
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-muted">Belum ada resep favorit.</div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $recipes->links() }}</div>
@endsection

