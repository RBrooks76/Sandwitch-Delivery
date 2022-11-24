@if($item)
    <div class="container">
        <div class="box-info">
            <form method="post" action="{{route('accept_order')}}">
                <table class="table table-hover table-x">
                    <thead>
                    <tr>
                        <th scope="col">01 CUSTOMER INFO</th>
                        <th scope="col">Add On’s :</th>
                        <th scope="col">Special Request :</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($item->Items as $r)
                        <tr>
                            <td><a href="#"><img style="width: 50px;height: 50px;"
                                                 src="{{$item != null ? $r->Products->img() : ""}}"
                                                 alt="{{$item != null ? $r->Products->name : ""}}"></a> {{$item != null ? $r->Products->name : ""}}
                            </td>
                            <td>
                                @foreach($r->OrderProductsFeature as $r2)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox"
                                               id="inlineCheckbox{{$r2->ProductsFeature->id}}"
                                               value="option2">
                                        <label class="form-check-label"
                                               for="inlineCheckbox{{$r2->ProductsFeature->id}}">{{$r2->ProductsFeature->name}}</label>
                                    </div>
                                @endforeach
                            </td>
                            <td>

                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
                @if($status != 5)
                    {{csrf_field()}}
                    <input name="order_id" value="{{$item->order_id}}" type="hidden">
                    <button type="submit">Accepted</button>
                @endif
            </form>
        </div>
    </div>

@endif
