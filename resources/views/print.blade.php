<html>
    <style>
        body{
            font-family: "Helvetica";
            background: #F7F7F7;
        }
        h4, h3{
            margin: 0px;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <body>
        <div style="width: 100%; margin: 0px auto">
            <div style="text-align: center;"><img src="{{ URL('/') }}/public/login_style/assets/img/Sandwich Map-1.png" width="80"></div>
            <h1 style="text-align: center;">Sandwich Map</h1>
            <div style="text-align: center">SANDWICH MAP LLC &nbsp;&nbsp; ساندويش ماب ذ.م.م.</div>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <h2 style="text-align: center">{{ $order->restaurant->user->name }} - {{ $order->City->name }}</h2>
            <h4 style="text-align: center; margin-bottom: 5px;">Order# {{ $order->id }}</h4>
            <h4 style="text-align: center; margin-bottom: 5px;">{{ $order->is_pickup ? 'Pickup' : 'Delivery' }} Order</h4>
            <h4 style="text-align: center; margin-bottom: 5px;">Date: {{ $order->created_at->format('d-m-Y') }}</h4>
            <h4 style="text-align: center; margin-bottom: 5px;">Time: {{ $order->created_at->format('H:i:s') }}</h4>
            <h4 style="text-align: center; margin-bottom: 5px;">Main Reading</h4>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            @foreach ($products as $item)
                <h3 style="float: left">{{ $item->products->name }} <small>({{ $item->products->amount }} x {{ $item->qun }})</small></h3>
                <h3 style="float: right">{{ $item->products->amount * $item->qun }} AED</h3>
                <div style="clear: both"></div>
                
                @if(count($item->product_addons) > 0)
                    <div><b>Add Ons:</b></div>
                    @foreach ($item->product_addons as $addon)
                        <div style="float: left">({{ $addon->ProductsFeature->name }} x {{ $addon->quantity }})</div>
                        <div style="float: right">{{ $addon->ProductsFeature->amount }} AED</div>
                        <div style="clear: both"></div>
                    @endforeach
                @endif
                
                @if($item->special_request != "")
                    <div style="margin-top: 10px"><b>Special Request</b></div>
                    <p>{{ $item->special_request }}</p>
                @endif
                
                <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
                
            @endforeach
            
            <h3 style="float: left">Sub Total: </h3>
            <h3 style="float: right">{{ $sub_total }} AED</h3>
            <div style="clear: both"></div>
            
            <h3 style="float: left; margin-top: 10px">Delivery Fees: </h3>
            <h3 style="float: right; margin-top: 10px">{{ $delivery_fee }} AED</h3>
            <div style="clear: both"></div>
            
            @if($order->discount_code != "")
                <h3 style="float: left; margin-top: 10px">Discounted ({{ $order->discount_code }}): </h3>
                <h3 style="float: right; margin-top: 10px">{{ $order->discounted_amount }} AED</h3>
                <div style="clear: both"></div>
            @endif
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <h1 style="text-align: center">Total</h1>
            <h1 style="text-align: center">{{ $order->total - $order->discounted_amount }} AED</h1>
            
            
            <div style="text-align: center; margin-top: 40px; margin-bottom: 10px"><img src="{{ URL('/') }}/public/login_style/assets/img/Sandwich Map-1.png" width="40"></div>
            <h3 style="text-align: center; margin-bottom: 10px">Sandwich Map</h3>
            <div style="text-align: center">SANDWICH MAP LLC &nbsp;&nbsp; ساندويش ماب ذ.م.م.</div>
            <div style="background: #000; color: #FFF; padding: 10px 20px; margin-top: 10px; font-size: 20px; margin-bottom: 10px; text-align: center; border-radius: 10px"><a href="" style="color: #FFF; text-decoration: none">WWW.SANDWICHMAP.COM</a></div>
        </div>
    </body>
</html>