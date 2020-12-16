@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')
<div class="page-wrapper">
    <div class="page-content--bge5">
        <div class="container">
            <div class="login-wrap">
                <div class="login-content bg-white p-4">
                    <div class="bg-info p-3 mb-3 text-center">
                        <h3 class="m-0">Forgot Password</h3>
                    </div>
                    @if (session('status'))
                     <div class="alert alert-success text-center" role="alert">
                       {{ session('status') }}
                     </div>
                    @endif
                    <div class="login-form">
                        <form action="{{ route('password.email') }}" method="post">
                          @csrf
                            <div class="form-group">
                                <label>Email </label>
                                <input class="form-control" type="email" value="{{ old('email') }}" name="email" placeholder="Email" required>
                                
                                @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>
                                    <a href="{{ route('login') }}">Back to login</a>
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