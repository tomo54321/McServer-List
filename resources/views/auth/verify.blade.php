@extends('layouts.app',[
"pagename"=>"Email verification required"
])

@section('content')
<div class="container">
    <h1>Email verification required</h1>
    @if (session('resent'))
    <div class="alert alert-success" role="alert">
        {{ __('A new verification email has been sent to your email address, please allow up to 5 minutes for it to arrive.') }}
    </div>
    @endif

    <div class="card">
        <div class="card-header">Verify Email</div>
        <div class="card-body">
            <p>Before you can begin adding or editing your servers you must verify your email address, please be sure to check your inbox and junk box.</p>
            <p>If the verification email is missing you can request a new one by clicking the link below.</p>

            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <center>
                <button type="submit"
                    class="btn btn-primary">{{ __('click here to request another') }}</button></center>
            </form>
        </div>
    </div>


</div>
@endsection