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
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
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
                    </div>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </section> <!-- End Conainer -->


@endsection
