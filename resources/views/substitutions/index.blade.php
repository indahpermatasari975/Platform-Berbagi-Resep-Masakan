@extends('layouts.app')

@section('title', 'Substitusi Bahan - ResepKita')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-arrow-repeat text-warning"></i> Substitusi Bahan</h2>
        <div class="text-muted">Cari alternatif bahan saat stok dapur tidak lengkap.</div>
    </div>

    <form method="GET" action="{{ route('substitutions.index') }}" class="d-flex gap-2">
        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari bahan...">
        <button class="btn btn-warning fw-semibold">Cari</button>
    </form>
</div>

<div class="row g-4">
    @forelse ($substitutions as $sub)
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-arrow-left-right"></i></div>
                        <div>
                            <div class="text-muted small">Ganti bahan</div>
                            <h5 class="fw-bold mb-0">{{ $sub->ingredient_name }}</h5>
                        </div>
                    </div>
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="text-muted small">Alternatif</div>
                        <div class="fs-5 fw-semibold">{{ $sub->substitute_name }}</div>
                    </div>
                    @if (!empty($sub->notes))
                        <p class="text-muted mb-0">{{ $sub->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state text-center">
                <h4 class="fw-bold mb-2">Substitusi Tidak Ditemukan</h4>
                <p class="mb-0">Coba kata kunci lain seperti susu, telur, tepung, atau mentega.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">{{ $substitutions->withQueryString()->links() }}</div>
@endsection
