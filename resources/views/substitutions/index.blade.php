@extends('layouts.app')

@section('title', 'Substitusi Bahan - ResepKita')

@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            <i class="bi bi-arrow-repeat text-warning"></i>
            Substitusi Bahan
        </h2>

        <div class="text-muted">
            Cari alternatif bahan saat stok dapur tidak lengkap.
        </div>
    </div>

    <div class="d-flex gap-2">

        {{-- Tombol tambah hanya admin --}}
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('substitutions.create') }}"
                   class="btn btn-warning">
                    <i class="bi bi-plus-lg"></i>
                    Tambah
                </a>
            @endif
        @endauth

        <form method="GET"
              action="{{ route('substitutions.index') }}"
              class="d-flex gap-2">

            <input type="text"
                   name="q"
                   value="{{ $q }}"
                   class="form-control"
                   placeholder="Cari bahan...">

            <button class="btn btn-warning">
                Cari
            </button>

        </form>

    </div>

</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($substitutions->isEmpty())

    <div class="empty-state text-center">
        <h4 class="fw-bold mb-2">
            Belum ada data substitusi bahan.
        </h4>
    </div>

@else

<div class="table-responsive">

    <table class="table table-hover align-middle bg-white shadow-sm">

        <thead class="table-warning">

            <tr>
                <th>Bahan Asli</th>
                <th>Bahan Pengganti</th>
                <th>Catatan</th>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <th width="180">Aksi</th>
                    @endif
                @endauth

            </tr>

        </thead>

        <tbody>

            @foreach ($substitutions as $sub)

                <tr>

                    <td class="fw-semibold">
                        {{ $sub->ingredient_name }}
                    </td>

                    <td>
                        {{ $sub->substitute_name }}
                    </td>

                    <td>
                        {{ $sub->notes ?: '-' }}
                    </td>

                    {{-- Tombol aksi hanya admin --}}
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <td>

                                <a href="{{ route('substitutions.edit', $sub) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('substitutions.destroy', $sub) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            onclick="return confirm('Hapus data ini?')"
                                            class="btn btn-sm btn-danger">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </td>
                        @endif
                    @endauth

                </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endif

<div class="mt-4 d-flex justify-content-center">
    {{ $substitutions->withQueryString()->links() }}
</div>

@endsection
