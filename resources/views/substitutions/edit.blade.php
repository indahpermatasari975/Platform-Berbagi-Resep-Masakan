@extends('layouts.app')

@section('title','Edit Substitusi')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        <h3 class="fw-bold mb-4">
            Edit Substitusi Bahan
        </h3>

        <form action="{{ route('substitutions.update',$substitution) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Bahan Asli</label>
                <input type="text"
                       name="ingredient_name"
                       class="form-control"
                       value="{{ $substitution->ingredient_name }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Bahan Pengganti</label>
                <input type="text"
                       name="substitute_name"
                       class="form-control"
                       value="{{ $substitution->substitute_name }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Catatan</label>
                <textarea name="notes"
                          class="form-control">{{ $substitution->notes }}</textarea>
            </div>

            <button class="btn btn-warning">
                Update
            </button>

        </form>

    </div>
</div>

@endsection
