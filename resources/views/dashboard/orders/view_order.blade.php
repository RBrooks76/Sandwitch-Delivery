@extends('dashboard.layouts.app')

@section('title')
    View Orders
@endsection

@section('create_btn'){{route('dashboard_orders.index')}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="clearfix">
                        <div class="float-left">
                            <h3 class="card-title mb-0">#{{$item->name}}</h3>
                        </div>
                        <div class="float-right">
                            <h3 class="card-title">Date: {{$item->date()}}</h3>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6">
                            <p class="h3">Details Clients</p>
                            <address>
                                <strong>Name</strong> : {{$item->name}}<br>
                                <hr>
                                <strong>Phone</strong> : {{$item->phone}}<br>
                            </address>
                        </div>
                        <div class="col-lg-6">
                            <p class="h3">Details Restaurant:</p>
                            <address>
                                <strong>Name Restaurant</strong>
                                :
                                @if($item->Items->count() != 0 )
                                    @foreach($item->Items as $r)
                                        {{$r->Products->Restaurant->name}}
                                        ,
                                    @endforeach
                                @endif

                                <br>
                                <hr>
                                <strong>Name Food</strong> :

                                @if($item->Items->count() != 0 )
                                    @foreach($item->Items as $r)
                                        {{$r->Products->name}}
                                        ,
                                    @endforeach
                                @endif
                                <br>
                                <hr>
                                <strong>Price</strong> : {{$item->total}}<br>
                                <hr>

                                <strong>Photos</strong>: <br>
                                <div class="box-imgs">
                                    <ul id="lightgallery" class="list-unstyled row" lg-uid="lg0">


                                        @if($item->Items->count() != 0 )
                                            @foreach($item->Items as $r)
                                                <li class="col-md-2 border-bottom-0"
                                                    data-responsive="assets/images/media/12.jpg"
                                                    data-src="assets/images/media/12.jpg"
                                                    data-sub-html="<h4>Gallery Image 12</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>"
                                                    lg-event-uid="&amp;1">
                                                    <a href="">
                                                        <img class="img-responsive mb-0" src="{{$r->Products->img()}}"
                                                             alt="Thumb-2">
                                                    </a>
                                                </li>
                                            @endforeach
                                        @endif

                                    </ul>
                                </div>
                            </address>
                        </div>

                    </div>
                    <div class="table-responsive push">
                        <table class="table table-bordered table-hover mb-0 text-nowrap">
                            <tbody>
                            <tr class=" ">
                                <th>Name Order</th>
                                <th class="text-center">Description</th>
                                <th class="text-right">Quantity</th>
                            </tr>
                            <tr>

                                @if($item->Items->count() != 0 )
                                    @foreach($item->Items as $r)
                                        <td class="text-center">{{$r->Products->name}}</td>
                                        <td>
                                            <div class="text-muted">
                                                <div class="text-muted">
                                                    {!! $r->Products->summary !!}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{$r->qun}}</td>
                                    @endforeach
                                @endif

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-info mb-1" onclick="javascript:window.print();"><i
                            class="si si-printer"></i> Print
                    </button>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>

@endsection

