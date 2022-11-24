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
            <div style="text-align: center;"><img src="<?php echo e(URL('/')); ?>/public/login_style/assets/img/Sandwich Map-1.png" width="80"></div>
            <h1 style="text-align: center;">Sandwich Map</h1>
            <div style="text-align: center">SANDWICH MAP LLC &nbsp;&nbsp; ساندويش ماب ذ.م.م.</div>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <h2 style="text-align: center"><?php echo e($order->restaurant->user->name); ?> - <?php echo e($order->City->name); ?></h2>
            <h4 style="text-align: center; margin-bottom: 5px;">Order# <?php echo e($order->id); ?></h4>
            <h4 style="text-align: center; margin-bottom: 5px;"><?php echo e($order->is_pickup ? 'Pickup' : 'Delivery'); ?> Order</h4>
            <h4 style="text-align: center; margin-bottom: 5px;">Date: <?php echo e($order->created_at->format('d-m-Y')); ?></h4>
            <h4 style="text-align: center; margin-bottom: 5px;">Time: <?php echo e($order->created_at->format('H:i:s')); ?></h4>
            <h4 style="text-align: center; margin-bottom: 5px;">Main Reading</h4>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <h3 style="float: left"><?php echo e($item->qun); ?> <?php echo e($item->products->name); ?></h3>
                <h3 style="float: right"><?php echo e($item->products->amount); ?> AED</h3>
                <div style="clear: both"></div>
                
                <?php if(count($item->product_addons) > 0): ?>
                    <div><b>Add Ons:</b></div>
                    <?php $__currentLoopData = $item->product_addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="float: left">(<?php echo e($addon->ProductsFeature->name); ?> x <?php echo e($addon->quantity); ?>)</div>
                        <div style="float: right"><?php echo e($addon->ProductsFeature->amount); ?> AED</div>
                        <div style="clear: both"></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                
                <?php if($item->special_request != ""): ?>
                    <div style="margin-top: 10px"><b>Special Request</b></div>
                    <p><?php echo e($item->special_request); ?></p>
                <?php endif; ?>
                
                <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
                
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <h3 style="float: left">Sub Total: </h3>
            <h3 style="float: right"><?php echo e($sub_total); ?> AED</h3>
            <div style="clear: both"></div>
            
            <h3 style="float: left; margin-top: 10px">Delivery Fees: </h3>
            <h3 style="float: right; margin-top: 10px"><?php echo e($delivery_fee); ?> AED</h3>
            <div style="clear: both"></div>
            
            <?php if($order->discount_code != ""): ?>
                <h3 style="float: left; margin-top: 10px">Discounted (<?php echo e($order->discount_code); ?>): </h3>
                <h3 style="float: right; margin-top: 10px"><?php echo e($order->discounted_amount); ?> AED</h3>
                <div style="clear: both"></div>
            <?php endif; ?>
            
            <div style="height: 2px; background: #000; width: 70%; margin: 15px auto"></div>
            
            <h1 style="text-align: center">Total</h1>
            <h1 style="text-align: center"><?php echo e($order->total - $order->discounted_amount); ?> AED</h1>
            
            
            <div style="text-align: center; margin-top: 40px; margin-bottom: 10px"><img src="<?php echo e(URL('/')); ?>/public/login_style/assets/img/Sandwich Map-1.png" width="40"></div>
            <h3 style="text-align: center; margin-bottom: 10px">Sandwich Map</h3>
            <div style="text-align: center">SANDWICH MAP LLC &nbsp;&nbsp; ساندويش ماب ذ.م.م.</div>
            <div style="background: #000; color: #FFF; padding: 10px 20px; margin-top: 10px; font-size: 20px; margin-bottom: 10px; text-align: center; border-radius: 10px"><a href="" style="color: #FFF; text-decoration: none">WWW.SANDWICHMAP.COM</a></div>
        </div>
    </body>
</html><?php /**PATH /home/mapstore/public_html/resources/views/print.blade.php ENDPATH**/ ?>