@extends('layouts.app',[
    "pagename"=>"Account Settings"
])

@section('content')
<div class="container">
    <h1>Account Settings</h1>

    @if(\Session::has("success"))
        <div class="alert alert-success">{{\Session::get("success")}}</div>
    @endif

    <div class="card">
        <div class="card-header">Account Details</div>
        <div class="card-body">
            <form action="{{route('account.settings.details')}}" method="POST">
                @csrf
                @method("put")
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{old('username') ?? $user->username}}" required autocomplete="off"/>
                    @error("username")
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div> 
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email') ?? $user->email}}" required autocomplete="email"/>
                    @error("email")
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div> 
                <div class="form-group">
                    <input type="submit" class="btn btn-success" value="Save Changes" />
                </div>
            </form>
        </div>
    </div>

    <div class="card my-4">
        <div class="card-header">Change Password</div>
        <div class="card-body">
            <form action="{{route('account.settings.password')}}" method="POST">
                @csrf
                @method("put")
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required autocomplete="off"/>
                    @error("current_password")
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" required autocomplete="off"/>
                    @error("new_password")
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required autocomplete="off"/>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-success" value="Save Changes" />
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Danger Zone</div>
        <div class="card-body">
            <p>Removing your account will also remove your server listings which includes all data, images and other information you have provided or have collected on your servers.</p>
            <form action="{{route('account.destroy')}}" method="POST">
                @csrf
                @method("delete")
                <div class="row align-items-center">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" required autocomplete="off" />
                            @error("confirm_password")
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <input type="submit" class="btn btn-danger btn-block" value="Delete Account" />
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection