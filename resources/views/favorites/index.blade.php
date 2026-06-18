@extends('layouts.app')

@section('title', 'Favorit Saya - ResepKita')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-heart text-danger"></i> Favorit Saya</h2>
        <div class="text-muted">Resep yang kamu simpan agar mudah ditemukan lagi.</div>
    </div>
    <a href="{{ route('recipes.index') }}" class="btn btn-outline-warning fw-semibold">
        <i class="bi bi-search"></i>
        Cari Resep
    </a>
</div>

<div class="row g-4">
    @forelse ($recipes as $recipe)
        @include('recipes.partials.recipe-card', ['recipe' => $recipe])
    @empty
        <div class="col-12">
            <div class="empty-state text-center">
                <h4 class="fw-bold mb-2">Belum Ada Favorit</h4>
                <p class="mb-3">Tekan tombol hati pada kartu resep untuk menyimpannya ke halaman ini.</p>
                <a href="{{ route('recipes.index') }}" class="btn btn-warning">Jelajahi Resep</a>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">{{ $recipes->links() }}</div>
@endsection
