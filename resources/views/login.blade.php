@extends('master')

@section('content')

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card py-4 px-4">
        <div class="text-center">
        <img src="{{ asset('images/image1.png') }}" width="350">

        </div>

        <form action="{{ route('login.store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="text" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                @error('email')
                    <span>{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

       

        
    </div>
</div>

@endsection