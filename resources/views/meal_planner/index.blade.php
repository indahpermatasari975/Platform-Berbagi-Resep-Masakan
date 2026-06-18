@extends('layouts.app')

@section('title', 'Meal Planner - ResepKita')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-calendar3 text-warning"></i> Meal Planner</h2>
        <div class="text-muted">Jadwal makan yang sudah kamu tambahkan dari halaman detail resep.</div>
    </div>
    <a href="{{ route('recipes.index') }}" class="btn btn-warning fw-semibold">
        <i class="bi bi-plus-lg"></i>
        Tambah Menu
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle m-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis Makan</th>
                        <th>Resep</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td class="fw-semibold">{{ \Illuminate\Support\Carbon::parse($item->meal_date)->format('d M Y') }}</td>
                            <td><span class="badge bg-warning text-dark">{{ $item->meal_type }}</span></td>
                            <td>
                                @if ($item->recipe)
                                    <a class="text-decoration-none fw-semibold" href="{{ route('recipes.show', $item->recipe) }}">
                                        {{ $item->recipe->title }}
                                    </a>
                                @else
                                    <span class="text-muted">Resep tidak tersedia</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($item->recipe)
                                    <a href="{{ route('recipes.show', $item->recipe) }}" class="btn btn-sm btn-outline-warning">Detail</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data meal planner.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">{{ $items->links() }}</div>
@endsection
