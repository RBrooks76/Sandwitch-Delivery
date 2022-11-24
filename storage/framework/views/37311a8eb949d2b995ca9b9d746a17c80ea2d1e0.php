<?php $__env->startSection('title'); ?>
    Login System
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Login 17 start -->
    <div class="login-17">
        <div class="container">
            <div class="col-md-12 pad-0">
                <div class="row login-box-6">
                    <div class="logo">
                        <img src="<?php echo e($path); ?>login_style/assets/img/Sandwich Map-1.png" width="80" />
                    </div>
                    <div class="col-lg-5 col-md-12 col-sm-12 col-pad-0 bg-img align-self-center none-992">
                        <a href="/">
                            <img src="public/upload/setting/1611862106.png" class="logo" alt="<?php echo e(setting()->name); ?>">
                        </a>
                        <p>Sandwich map head office department for direct communication please press support or whats up button</p>
                        <a href="https://api.whatsapp.com/send?1=pt_BR&phone=+971501212770"
                           class="btn-outline">Support</a>
                    </div>
                    <div class="col-lg-7 col-md-12 col-sm-12 col-pad-0 align-self-center">
                        <div class="login-inner-form">
                            <div class="details">
                                <h3>Login</h3>
                                <?php if ($__env->exists("layouts.msg")) echo $__env->make("layouts.msg", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <form method="POST" action="<?php echo e(route('login')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <input class="form-control <?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>"
                                               type="text" id="email" value="<?php echo e(old('email')); ?>" name="email"
                                               placeholder="E-mail Address" required>
                                        <?php if($errors->has('email')): ?>
                                            <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control <?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>"
                                               type="password" name="password" placeholder="Password" required>
                                        <?php if($errors->has('password')): ?>
                                            <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                    </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="checkbox clearfix">
                                        <div class="form-check checkbox-theme">
                                            <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn-md btn-theme btn-block"> <?php echo app('translator')->get('site.login'); ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login 17 end -->

<?php $__env->stopSection(); ?>


<?php echo $__env->make('auth.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mapstore/public_html/resources/views/auth/login.blade.php ENDPATH**/ ?>