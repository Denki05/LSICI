@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center">
    <div class="col-md-5">
        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-header bg-primary text-white text-center py-3 rounded-top">
                <h4 class="mb-0">{{ __('Login') }}</h4>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">{{ __('Email') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required autocomplete="current-password">
                        @error('password')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold py-2 btn-hover">
                            {{ __('Login') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Efek Hover untuk Tombol Login */
    .btn-hover:hover {
        background-color: #0056b3;
        transition: background-color 0.3s ease-in-out;
    }
</style>
@endsection