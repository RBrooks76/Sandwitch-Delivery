<html>
    <head>
        <style>
            @page {
                size: auto;
                margin: 0mm;
                padding-bottom: 10cm;
            }

            body {
                font-family: system-ui;
                margin-top: 1cm;
                width: 7cm;
            }

            table th {
                text-align: start;
                width: 3.5cm;
                padding-top: 1px;
                padding-bottom: 1px;
            }

            table {
                margin-bottom: 0.2cm;
                width: 8cm;
                border-collapse: collapse;
            }

            .title {
                text-align: center;
            }

            h5,
            h3 {
                text-align: center;
            }

            .summary h5 {
                margin: 0.1cm;
            }

            .order-info h5 {
                margin: 0.1cm;
            }

            .sub-totals h5 {
                margin: 0.2cm;
            }

            .divider {
                margin: 0.3cm;
            }

            .item-name {
                text-align: center;
                margin-top: 0.35cm;
            }

            .product-item ul {
                margin-top: 0.15cm;
            }

            ul {
                margin-left: 1cm;
            }

            ul h5 {
                margin: 0.1cm;
                text-align: left;
            }

            .special-request-label {
                text-align: center;
                margin-left: 1cm;
                margin-bottom: 0.1cm;
            }

            .special-request {
                text-align: center;
                margin-left: 1cm;
                margin: 0.1cm;
            }

        </style>
    </head>

    <body>
        
        
        Dear Partner You Have One New Order {{ sprintf("%05d", $order->id) }} <br>
        Shop name: {{ $order->restaurant->user->name }}<br>
        Customer name: {{ $order->client_name }}<br>
        Customer Mobile Number: {{ $order->phone }}<br>
        Total Bill:  {{ $order->total }}<br>
        Location map: https://maps.google.com/maps/search/?api=1&query={{ $order->log }},{{ $order->lat }}<br>
        Thank you for using Sandwich Map
        <br><br><br>
        
        <h1 class="title">Sandwich Map</h1>
        <div class="summary">
            <h5>{{ $Restaurant->user->name }} - {{ $order->City->name }}</h5>
            <h5>Order# {{ $order->id }}</h5>
            <h5>{{ $order->is_pickup ? 'Pickup' : 'Delivery' }} Order</h5>
            <h5>{{ $order->created_at->format('d-m-Y H:i') }}</h5>
        </div>
        <h5 class="divider">-----------------------------------------</h5>
        <div class="order-info">
            <h5>Name: {{ $order->client_name }}</h5>
            <h5>Mobile: {{ $order->phone }}</h5>
            <h5>Table/Car: {{ $order->car_number ?? '-----' }}</h5>
        </div>
        <h5 class="divider">-----------------------------------------</h5>
        @foreach ($products as $item)
            <div class="product-item">
                <div class="item-name">{{ $item->qun }} {{ $item->products->name }} {{ $item->products->amount }} AED</div>
                <ul>
                    <h5>Add Ons:</h5>
                    @foreach ($item->product_addons as $addon)
                        <li>({{ $addon->ProductsFeature->name }} x {{ $addon->quantity }}) {{ $addon->ProductsFeature->amount }} AED</li>
                    @endforeach
                </ul>
                <h5 class="special-request-label">Special Request:</h5>
                <p class="special-request">{{ $item->special_request }}</p>
            </div>
        @endforeach
        <h5 class="divider">-----------------------------------------</h5>
        <div class="sub-totals">
            <h5>Sub Total: {{ $sub_total }} AED</h5>
            <h5>Delivery Fees: {{ $delivery_fee }} AED</h5>
        </div>
        <h3>Bill Total: {{ $order->total }}</h3>
    </body>
</html>
