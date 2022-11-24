@extends('layouts.app')

@section('title')
    {{$lang->Account}}
@endsection

@section('content')

    <section class="space-ptb">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        <h1>{{$lang->Account}}</h1>
                        <p>{{lang_name('Account_Desc')}}</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-lg-around position-relative pt-5">
                <div class="col-lg-6 col-md-6 col-md-offset-3">
                    <div class="p-4 p-md-5 bg-white shadow">
                        <h3>{{lang_name('Account_Desc_form')}}</h3>
                        <form class="mt-4 form-horizontal register_user ajaxForm" data-name="register_user" method="POST" action="{{ route('register_user') }}">
                            @csrf
                            <input id="id" name="id" type="hidden">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" id="university_name" name="university_name" placeholder="{{lang_name('University_Name')}}">
                            </div>
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" id="degree" name="degree" placeholder="{{lang_name('Degree')}}">
                            </div>
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{lang_name('Full_Name')}}">
                            </div>
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" id="email" name="email"
                                       placeholder="{{lang_name('Email')}}">
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" class="form-control" id="password"
                                       name="password"
                                       placeholder="{{lang_name('Password')}}">
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="{{lang_name('Password_Confirm')}}">
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">{{lang_name('Send_Application')}}<i
                                            class="fas fa-arrow-right pl-3"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="contact-bg-logo">
                    <i class="fas fa-comment"></i>
                </div>
            </div>
        </div>
    </section>

@endsection
