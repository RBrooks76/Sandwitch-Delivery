@extends('layouts.app')

@section('title')
    Rest Page password
@endsection

@section('content')

    <section class="register">
        <div class="breadcrumns">
            <h1>
                Reset Password
            </h1>
        </div>
        <div class="container container-custom">
            <div class="box-form">
                <h3>Rest now - fill the form</h3>
                @include("layouts.msg")
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="text" class="form-control" id="email" name="email"
                                       placeholder="Your Email Address">
                            </div>
                            @if ($errors->has('email'))
                                <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password">
                                Password
                            </label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" >

                                @if ($errors->has('password'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">
                                Confirm Password
                            </label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                            </div>
                        </div>
                        
                    </div>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </section> <!-- End Conainer -->


@endsection
