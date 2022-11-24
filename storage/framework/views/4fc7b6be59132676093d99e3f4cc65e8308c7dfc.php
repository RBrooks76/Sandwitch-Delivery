<?php if(session()->has('error')): ?>
    <div class="alert alert-danger">
        <p><?php echo e(session()->get('error')); ?></p>
    </div>
<?php endif; ?>
<?php if(session()->has('success')): ?>
    <div class="alert alert-success">
        <p><?php echo e(session()->get('success')); ?></p>
    </div>
<?php endif; ?>
<?php if(session()->has('warning')): ?>
    <div class="alert alert-warning">
        <p><?php echo e(session()->get('warning')); ?></p>
    </div>
<?php endif; ?>
<?php if(session()->has('info')): ?>
    <div class="alert alert-info">
        <p><?php echo e(session()->get('info')); ?></p>
    </div>
<?php endif; ?>
<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?><?php /**PATH /home/sandwichmap/public_html/resources/views/layouts/msg.blade.php ENDPATH**/ ?>