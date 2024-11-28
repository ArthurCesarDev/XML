@extends('master')

@section('content')

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card py-4 px-4">
        <div class="text-center">
            <img src="{{ asset('images/image1.png') }}" width="350">
        </div>

        <form action="{{ route('register.store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" id="name" aria-describedby="nameHelp">
                @error('name')
                    <span>{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp">
                @error('email')
                    <span>{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password">
                @error('password')
                    <span>{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>

    </div>
</div>

@endsection
