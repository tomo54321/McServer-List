@extends('layouts.app',[
    "pagename"=>"Register"
])

@section('content')
<div class="container">
    <h1>Login</h1>

    <div class="row">
        <div class="col-12 col-md-6">
            <form action="" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email Address" autocomplete="email" value="{{old('email')}}" required />
                    @error("email")
                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password" autocomplete="off" required />
                    @error("password")
                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <input type="submit" class="btn btn-primary" value="Login" />
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{route('register')}}" class="btn btn-secondary">Register</a>
                        </div>
                    </div>
                    <a href="{{route('password.request')}}" class="d-block text-center mt-4">Forgotten your password?</a>
                </div>
            </form>
        </div>
        <div class="d-none d-md-block col-6">
            <h2>Why Login?</h2>
            <p>Here's just a few reasons to login to {{env("APP_NAME", "Laravel")}}:</p>
            @include("includes.reasons")
        </div>
    </div>
</div>
@endsection
