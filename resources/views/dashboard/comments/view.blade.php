@extends('dashboard.layouts.app')

@section('title')
    {{$item->Restaurant->name}}
@endsection

@section('create_btn'){{route('dashboard_comments.index',['id'=>null,'restaurant_id'=>$item->restaurant_id])}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="clearfix">

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="h3">Details Comment</p>
                            <address>
                                <strong>Name Customer</strong> :     {{$item->Restaurant->name}}
                                <br>
                                <hr>
                                <strong>Phone</strong> :     {{$item->Restaurant->phone}}
                                <br>
                                <hr>
                                <strong>Date</strong> : {{$item->date()}}<br>
                                <hr>
                                <strong>Comment Content</strong>:<br><br>
                                {!! $item->comment !!}
                            </address>
                        </div>


                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>

@endsection
