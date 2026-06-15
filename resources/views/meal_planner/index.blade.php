@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
        <div>
            <h2 class="mb-1">Meal Planner</h2>
            <div class="text-muted small">Jadwal makan yang sudah kamu tambahkan</div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle m-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Resep</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->meal_date }}</td>
                                <td><span class="badge bg-warning text-dark">{{ $item->meal_type }}</span></td>
                                <td>
                                    <a class="text-decoration-none" href="{{ route('recipes.show', $item->recipe_id) }}">
                                        {{ $item->recipe?->title ?? '-' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted">Belum ada data meal planner.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
@endsection

