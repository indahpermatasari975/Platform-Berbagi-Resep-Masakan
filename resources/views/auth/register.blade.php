@extends('layouts.app')

@section('title', 'Register')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card shadow border-0">
            <div class="card-body p-4">

                <h2 class="text-center fw-bold mb-4">
                    Daftar Akun
                </h2>

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            required>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-warning w-100 text-white">

                        <i class="bi bi-person-plus-fill"></i>
                        Daftar
                    </button>

                    <div class="text-center mt-3">
                        Sudah punya akun?

                        <a href="{{ route('login') }}">
                            Login disini
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
