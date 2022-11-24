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
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <body>
        <div style="width: 100%; margin: 0px auto">
            <div style="text-align: center;"><img src="{{ URL('/') }}/public/login_style/assets/img/Sandwich Map-1.png" width="80"></div>
            <h1 style="text-align: center;">Sandwich Map</h1>
            <div style="text-align: center">SANDWICH MAP LLC &nbsp;&nbsp; ساندويش ماب ذ.م.م.</div>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <h2 style="text-align: center">{{ $RestName }} - {{ $CityName }}</h2>
            <h4 style="text-align: center; margin-bottom: 5px;">{{ date("d-m-Y") }}</h4>
            <h4 style="text-align: center; margin-bottom: 5px;">{{ date("H:i:s") }}</h4>
            <h4 style="text-align: center; margin-bottom: 5px;">Main Reading</h4>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <h3 style="margin-bottom: 5px;">First Order : &nbsp; {{ $FirstOrderDate }}</h3>
            <h3 style="margin-bottom: 5px;">Last Order : &nbsp; {{ $LastOrderDate }}</h3>
            
            <h3 style="margin-bottom: 5px;">First Order : &nbsp; #{{ $FirstOrderNo }}</h3>
            <h3 style="margin-bottom: 5px;">Last Order : &nbsp; #{{ $LastOrderNo }}</h3>
            <h3 style="margin-bottom: 5px;">Total Orders : &nbsp; {{ $TotalOrders }}</h3>
            <h3 style="margin-bottom: 5px;">Pickup Orders : &nbsp; {{ $PickupOrders }}</h3>
            <h3 style="margin-bottom: 5px;">Delivery Orders : &nbsp; {{ $DeliveryOrder }}</h3>
            <h3 style="margin-bottom: 5px;">Cash Orders : &nbsp; {{ $CashOrders }}</h3>
            <h3 style="margin-bottom: 5px;">Card Orders : &nbsp; {{ $CardOrder }}</h3>
            <h3 style="margin-bottom: 5px;">Online Orders : &nbsp; {{ $OnlineOrder }}</h3>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <div style="font-size: 30px; margin-top: 30px;">CATEGORIES</div>
            
            @foreach($NewCatArray as $CatName => $array)
                <h2 style="text-align: center">{{ $CatName }}</h2>
                
                @foreach($array as $NewArr)
                    <h3 style="float: left; margin-top: 10px;">{{ $NewArr["Name"] }}</h3>
                    <h3 style="float: right; margin-top: 10px;">{{ $NewArr["Amount"] }} AED</h3>
                    <div style="clear: both"></div>
                    <div style="float: left">Add On's</div>
                    <div style="float: right">{{ $NewArr["AdOns"] }} AED</div>
                    <div style="clear: both"></div>
                @endforeach
            @endforeach
            
            <div style="height: 2px; background: #000; width: 70%; margin: 30px auto"></div>
            
            <h3 style="margin-bottom: 5px;">Pickup Sales : &nbsp; {{ $PickupOrderSales }} AED</h3>
            <h3 style="margin-bottom: 5px;">Delivery Sales : &nbsp; {{ $DeliveryOrderSales }} AED</h3>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 30px auto"></div>
            
            <h3 style="margin-bottom: 5px;">Cash Sales : &nbsp; {{ $CashSales }} AED</h3>
            <h3 style="margin-bottom: 5px;">Card Sales : &nbsp; {{ $CardSales }} AED</h3>
            <h3 style="margin-bottom: 5px;">Online Sales : &nbsp; {{ $OnlineSales }} AED</h3>
            
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <h1 style="text-align: center">Total</h1>
            <h1 style="text-align: center">{{ $CashSales + $CardSales + $OnlineSales }} AED</h1>
            
            
            <div style="text-align: center; margin-top: 40px; margin-bottom: 10px"><img src="{{ URL('/') }}/public/login_style/assets/img/Sandwich Map-1.png" width="40"></div>
            <h3 style="text-align: center; margin-bottom: 10px">Sandwich Map</h3>
            <div style="text-align: center">SANDWICH MAP LLC &nbsp;&nbsp; ساندويش ماب ذ.م.م.</div>
            <div style="background: #000; color: #FFF; padding: 10px 20px; margin-top: 10px; font-size: 20px; margin-bottom: 10px; text-align: center; border-radius: 10px"><a href="" style="color: #FFF; text-decoration: none">WWW.SANDWICHMAP.COM</a></div>
        </div>
    </body>
</html>