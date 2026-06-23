@extends('layouts.app')

@section('title', 'Daftar Resep - ResepKita')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-journal-richtext text-warning"></i> Daftar Resep</h2>
        <p class="text-muted mb-0">Temukan resep nusantara dan internasional favoritmu.</p>
    </div>
    @auth
        <a href="{{ route('recipes.create') }}" class="btn btn-warning fw-semibold">
            <i class="bi bi-plus-lg"></i>
            Tambah Resep
        </a>
    @endauth
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('recipes.index') }}">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari judul, kategori, atau deskripsi...">
                <button class="btn btn-warning fw-semibold">Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <span class="badge bg-warning text-dark">Total Resep: {{ $recipes->total() }}</span>
    @if (request('q'))
        <a href="{{ route('recipes.index') }}" class="small text-decoration-none">Reset pencarian</a>
    @endif
</div>

@if ($recipes->count())
    <div class="row g-4">
        @foreach ($recipes as $recipe)
            @include('recipes.partials.recipe-card', ['recipe' => $recipe])
        @endforeach
    </div>
@else
    <div class="empty-state text-center">
        <h4 class="fw-bold mb-2">Resep Tidak Ditemukan</h4>
        <p class="mb-3">Tidak ada resep yang sesuai dengan pencarian kamu.</p>
        <a href="{{ route('recipes.index') }}" class="btn btn-warning">Tampilkan Semua Resep</a>
    </div>
@endif

<div class="mt-5 d-flex justify-content-center">
    {{ $recipes->withQueryString()->links() }}
</div>
@endsection
