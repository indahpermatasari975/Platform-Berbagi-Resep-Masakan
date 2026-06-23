@extends('layouts.app')

@section('title', 'Admin Resep - ResepKita')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-shield-check text-warning"></i> Admin Resep</h2>
        <p class="text-muted mb-0">Kelola approval resep yang dikirim pengguna.</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Author</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recipes as $recipe)
                        <tr>
                            <td class="fw-semibold">{{ $recipe->title }}</td>
                            <td>{{ $recipe->user->name ?? $recipe->author_name }}</td>
                            <td>{{ $recipe->category }}</td>
                            <td>
                                @if ($recipe->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    @if ($recipe->status === 'pending')
                                        <form action="{{ route('admin.recipes.approve', $recipe) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-warning btn-sm fw-semibold">
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('Hapus resep ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada resep.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $recipes->links() }}
</div>
@endsection
