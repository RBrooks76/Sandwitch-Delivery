<html>
    <head>
        <style>
            @page  {
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
        
        
        Dear Partner You Have One New Order <?php echo e(sprintf("%05d", $order->id)); ?> <br>
        Shop name: <?php echo e($order->restaurant->user->name); ?><br>
        Customer name: <?php echo e($order->client_name); ?><br>
        Customer Mobile Number: <?php echo e($order->phone); ?><br>
        Total Bill:  <?php echo e($order->total); ?><br>
        Location map: https://maps.google.com/maps/search/?api=1&query=<?php echo e($order->log); ?>,<?php echo e($order->lat); ?><br>
        Thank you for using Sandwich Map
        <br><br><br>
        
        <h1 class="title">Sandwich Map</h1>
        <div class="summary">
            <h5><?php echo e($Restaurant->user->name); ?> - <?php echo e($order->City->name); ?></h5>
            <h5>Order# <?php echo e($order->id); ?></h5>
            <h5><?php echo e($order->is_pickup ? 'Pickup' : 'Delivery'); ?> Order</h5>
            <h5><?php echo e($order->created_at->format('d-m-Y H:i')); ?></h5>
        </div>
        <h5 class="divider">-----------------------------------------</h5>
        <div class="order-info">
            <h5>Name: <?php echo e($order->client_name); ?></h5>
            <h5>Mobile: <?php echo e($order->phone); ?></h5>
            <h5>Table/Car: <?php echo e($order->car_number ?? '-----'); ?></h5>
        </div>
        <h5 class="divider">-----------------------------------------</h5>
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="product-item">
                <div class="item-name"><?php echo e($item->qun); ?> <?php echo e($item->products->name); ?> <?php echo e($item->products->amount); ?> AED</div>
                <ul>
                    <h5>Add Ons:</h5>
                    <?php $__currentLoopData = $item->product_addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>(<?php echo e($addon->ProductsFeature->name); ?> x <?php echo e($addon->quantity); ?>) <?php echo e($addon->ProductsFeature->amount); ?> AED</li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <h5 class="special-request-label">Special Request:</h5>
                <p class="special-request"><?php echo e($item->special_request); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <h5 class="divider">-----------------------------------------</h5>
        <div class="sub-totals">
            <h5>Sub Total: <?php echo e($sub_total); ?> AED</h5>
            <h5>Delivery Fees: <?php echo e($delivery_fee); ?> AED</h5>
        </div>
        <h3>Bill Total: <?php echo e($order->total); ?></h3>
    </body>
</html>
<?php /**PATH /home/mapstore/public_html/resources/views/EmailTemplates/print.blade.php ENDPATH**/ ?>