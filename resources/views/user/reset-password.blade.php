@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
<div class="page-wrapper">
    <div class="page-content--bge5">
        <div class="container">
            <div class="login-wrap">
                <div class="login-content bg-white p-4">
                    <div class="bg-info p-3 mb-3 text-center">
                        <h3 class="m-0">Reset Password</h3>
                    </div>
                    @if (session('status'))
                     <div class="alert alert-success text-center" role="alert">
                       {{ session('status') }}
                     </div>
                    @endif
                    @if (session('error'))
                     <div class="alert alert-danger text-center" role="alert">
                       {{ session('error') }}
                     </div>
                    @endif
                    <div class="login-form">
                        <form action="{{ route('password.update') }}" method="post">
                          @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" type="email" value="{{ old('email') }}" name="email" placeholder="Email" required>
                                @if ($errors->has('email'))
                                    <span class = "text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input class="form-control" type="password" name="password" placeholder="New Password" required>
                                @if ($errors->has('password'))
                                    <span class = "text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Confirm Password </label>
                                <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class = "text-danger">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>
                            <div class="login-checkbox">
                                <label>
                                    <a href="{{ route('login_page') }}">Back to login</a>
                                </label>
                            </div>
                            <button class="btn btn-lg btn-success w-100" type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop