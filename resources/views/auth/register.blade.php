@extends('layouts.app',[
    "pagename"=>"Register"
])

@section('content')
<div class="container">
    <h1>Register</h1>

    <div class="row">
        <div class="col-12 col-md-6">
            <form action="" method="POST">
                @csrf

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" placeholder="Username" autocomplete="username" value="{{old('username')}}" required />
                    @error("username")
                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                    @enderror
                </div>

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
                    @else
                        <small class="text-muted">Passwords must be at least 8 characters long, contain 1 uppercase, 1 lowercase letter and a number.</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirmation">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password-confirmation" placeholder="Confirm Password" autocomplete="off" required />
                </div>
                <div class="form-group">
                    {!! ReCaptcha::htmlFormSnippet() !!}
                    @error("g-recaptcha-response")
                        <div class="invalid-feedback d-block">{{$message}}</div>
                    @enderror
                </div>
                <div class="form-group text-muted">
                    <small>By clicking <b>Create Account</b> you have read and agree to our terms and conditions</small>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Create Account" />
                </div>
            </form>
        </div>
        <div class="d-none d-md-block col-6">
            <h2>Why Register?</h2>
            <p>Here's just a few reasons to join {{env("APP_NAME", "Laravel")}}:</p>
            @include("includes.reasons")
        </div>
    </div>
</div>
@endsection
