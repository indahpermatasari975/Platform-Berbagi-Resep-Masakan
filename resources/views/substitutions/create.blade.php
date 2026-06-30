@extends('layouts.app')

@section('title','Tambah Substitusi')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        <h3 class="fw-bold mb-4">
            Tambah Substitusi Bahan
        </h3>

        <form action="{{ route('substitutions.store') }}"
              method="POST">

            @csrf

            <div class="mb-3">
                <label>Bahan Asli</label>
                <input type="text"
                       name="ingredient_name"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label>Bahan Pengganti</label>
                <input type="text"
                       name="substitute_name"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label>Catatan</label>
                <textarea name="notes"
                          class="form-control"></textarea>
            </div>

            <button class="btn btn-warning">
                Simpan
            </button>

        </form>

    </div>
</div>

@endsection
