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
            <div style="text-align: center;"><img src="<?php echo e(URL('/')); ?>/public/login_style/assets/img/Sandwich Map-1.png" width="80"></div>
            <h1 style="text-align: center;">Sandwich Map</h1>
            <div style="text-align: center">SANDWICH MAP LLC &nbsp;&nbsp; ساندويش ماب ذ.م.م.</div>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <h2 style="text-align: center"><?php echo e($RestName); ?> - <?php echo e($CityName); ?></h2>
            <h4 style="text-align: center; margin-bottom: 5px;"><?php echo e(date("d-m-Y")); ?></h4>
            <h4 style="text-align: center; margin-bottom: 5px;"><?php echo e(date("H:i:s")); ?></h4>
            <h4 style="text-align: center; margin-bottom: 5px;">Main Reading</h4>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <h3 style="margin-bottom: 5px;">First Order : &nbsp; <?php echo e($FirstOrderDate); ?></h3>
            <h3 style="margin-bottom: 5px;">Last Order : &nbsp; <?php echo e($LastOrderDate); ?></h3>
            
            <h3 style="margin-bottom: 5px;">First Order : &nbsp; #<?php echo e($FirstOrderNo); ?></h3>
            <h3 style="margin-bottom: 5px;">Last Order : &nbsp; #<?php echo e($LastOrderNo); ?></h3>
            <h3 style="margin-bottom: 5px;">Total Orders : &nbsp; <?php echo e($TotalOrders); ?></h3>
            <h3 style="margin-bottom: 5px;">Pickup Orders : &nbsp; <?php echo e($PickupOrders); ?></h3>
            <h3 style="margin-bottom: 5px;">Delivery Orders : &nbsp; <?php echo e($DeliveryOrder); ?></h3>
            <h3 style="margin-bottom: 5px;">Cash Orders : &nbsp; <?php echo e($CashOrders); ?></h3>
            <h3 style="margin-bottom: 5px;">Card Orders : &nbsp; <?php echo e($CardOrder); ?></h3>
            <h3 style="margin-bottom: 5px;">Online Orders : &nbsp; <?php echo e($OnlineOrder); ?></h3>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <div style="font-size: 30px; margin-top: 30px;">CATEGORIES</div>
            
            <?php $__currentLoopData = $NewCatArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $CatName => $array): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <h2 style="text-align: center"><?php echo e($CatName); ?></h2>
                
                <?php $__currentLoopData = $array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $NewArr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <h3 style="float: left; margin-top: 10px;"><?php echo e($NewArr["Name"]); ?></h3>
                    <h3 style="float: right; margin-top: 10px;"><?php echo e($NewArr["Amount"]); ?> AED</h3>
                    <div style="clear: both"></div>
                    <div style="float: left">Add On's</div>
                    <div style="float: right"><?php echo e($NewArr["AdOns"]); ?> AED</div>
                    <div style="clear: both"></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 30px auto"></div>
            
            <h3 style="margin-bottom: 5px;">Pickup Sales : &nbsp; <?php echo e($PickupOrderSales); ?> AED</h3>
            <h3 style="margin-bottom: 5px;">Delivery Sales : &nbsp; <?php echo e($DeliveryOrderSales); ?> AED</h3>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 30px auto"></div>
            
            <h3 style="margin-bottom: 5px;">Cash Sales : &nbsp; <?php echo e($CashSales); ?> AED</h3>
            <h3 style="margin-bottom: 5px;">Card Sales : &nbsp; <?php echo e($CardSales); ?> AED</h3>
            <h3 style="margin-bottom: 5px;">Online Sales : &nbsp; <?php echo e($OnlineSales); ?> AED</h3>
            
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <h1 style="text-align: center">Total</h1>
            <h1 style="text-align: center"><?php echo e($CashSales + $CardSales + $OnlineSales); ?> AED</h1>
            
            
            <div style="text-align: center; margin-top: 40px; margin-bottom: 10px"><img src="<?php echo e(URL('/')); ?>/public/login_style/assets/img/Sandwich Map-1.png" width="40"></div>
            <h3 style="text-align: center; margin-bottom: 10px">Sandwich Map</h3>
            <div style="text-align: center">SANDWICH MAP LLC &nbsp;&nbsp; ساندويش ماب ذ.م.م.</div>
            <div style="background: #000; color: #FFF; padding: 10px 20px; margin-top: 10px; font-size: 20px; margin-bottom: 10px; text-align: center; border-radius: 10px"><a href="" style="color: #FFF; text-decoration: none">WWW.SANDWICHMAP.COM</a></div>
        </div>
    </body>
</html><?php /**PATH /home/mapstore/public_html/resources/views/OrderSummary.blade.php ENDPATH**/ ?>