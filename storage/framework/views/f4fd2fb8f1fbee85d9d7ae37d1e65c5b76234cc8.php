

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e($path . 'css/selectize.css'); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>
    Restaurant
<?php $__env->stopSection(); ?>

<?php $__env->startSection('create_btn'); ?><?php echo e(route('dashboard_restaurant.index')); ?><?php $__env->stopSection(); ?>
    <?php $__env->startSection('create_btn_btn'); ?> Close <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <form class="ajaxForm users" enctype="multipart/form-data" data-name="users" method="post" id="form" action=<?php echo e(route('dashboard_restaurant.post_data')); ?>>
        <?php echo e(csrf_field()); ?>


        <input id="id" name="id" value="" type="hidden">
        <input id="email_id" name="email_id" value="" type="hidden">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Restaurant Information</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>City</label>
                    <select multiple="single" name="city_id[]" id="city_id" class="filter-multi">
                        <?php $__currentLoopData = $city_id; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select multiple="multiple" name="category_id[]" id="category_id" class="filter-multi">
                        <?php $__currentLoopData = $category_id; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Name Restaurant</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Provide Restaurant Name">
                </div>

                <div class="form-group">
                    <label class="form-label">Restaurant Fees</label>
                    <input type="text" class="form-control" name="fees" id="fees" placeholder="Provide Restaurant Fees">
                </div>

                <div class="form-group">
                    <label class="form-label">Delivery Time</label>
                    <input type="text" class="form-control" name="delivery" id="delivery" placeholder="Provide Restaurant Delivery Time">
                </div>

                <div class="col-lg-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="mb-0 card-title">Logo Upload</h3>
                        </div>
                        <div class="card-body">
                            <input type="file" class="dropify" id="avatar" name="avatar" data-height="300" />
                        </div>
                    </div>
                </div><!-- COL END -->
            </div>

        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Restaurant Information</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Provide restaurant management email">
                </div>
                <div class="form-group">
                    <label class="form-label">Sub emails</label>
                    <input type="text" class="form-control" name="sub_emails" id="sub_emails" placeholder="Provide sub emails distinguish by comma">
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="text" class="form-control" name="password" id="password" placeholder="Provide restaurant management password">
                </div>

                <div class="form-group">
                    <label class="form-label">Password Confirmation</label>
                    <input type="text" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Provide restaurant management password">
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Number</label>
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Provide your phone number">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Landline Number</label>
                    <input type="text" class="form-control" name="landline_number" id="landline_number">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Working Hours</label>
                    <input type="text" class="form-control" name="opening_hours" id="opening_hours">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Address Map Link</label>
                    <input type="text" class="form-control" name="map_link" id="map_link">
                </div>
                
                <!--<div class="form-group">
                    <label class="form-label">Address Longtitude</label>
                    <input type="text" class="form-control" name="longtitude" id="longtitude">
                </div>-->

            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payment Information</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group form-elements m-0">
                            <div class="form-label">Cash On Delivery</div>
                        </div>
                    </div>
                    <div class="col-md-10 x-check-active">
                        <div class="material-switch pull-left" style="transform: translateY(16px);">
                            <input id="someSwitchOptionSuccess1" name="cash" type="checkbox" />
                            <label for="someSwitchOptionSuccess1" class="label-success"></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group form-elements m-0">
                            <div class="form-label">Creadit Card</div>
                        </div>
                    </div>
                    <div class="col-md-10 x-check-active">
                        <div class="material-switch pull-left" style="transform: translateY(16px);">
                            <input id="someSwitchOptionSuccess2" name="visa" type="checkbox" />
                            <label for="someSwitchOptionSuccess2" class="label-success"></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group form-elements m-0">
                            <div class="form-label"> Pay Online</div>
                        </div>
                    </div>
                    <div class="col-md-10 x-check-active">
                        <div class="material-switch pull-left" style="transform: translateY(16px);">
                            <input id="someSwitchOptionSuccess3" name="online" type="checkbox" onChange="paymentOnline()" />
                            <label for="someSwitchOptionSuccess3" class="label-success"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="id01" class="modal-online">
            <form class="modal-content-online animate" action="#" method="post">
                <div class="container" style="background-color: white; width: 30%; text-align: center;">
                    <label for="uname"><b style="font-size: 30px;">Credit Card</b></label>
                    <input type="text" class="form-control" name="card" id="card" placeholder="Card Number">
                    <input type="text" class="form-control" name="code" id="code" placeholder="Security Code">
                    <input type="text" class="form-control" name="mm" id="mm" placeholder="MM" style="width: 35%; float: left;">
                    <input type="text" class="form-control" name="yy" id="yy" placeholder="YY" style="width: 35%; float: right;">
                    <input type="text" class="form-control" name="first" id="first" placeholder="First Name">
                    <input type="text" class="form-control" name="last" id="last" placeholder="Last Name">
                    <button type="submit" class="btn btn-success mt-1">Next</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-footer">
                <button class="btn btn_re btn-success mt-1" onclick="addRestaurant(event);">Add Restaurant</button>
                <input type="hidden" class="clicked" flag="" value="0">
            </div>
        </div>

    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="<?php echo e($path . 'js/selectize.min.js'); ?>"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            "use strict";
            //Code here.

            var url = $(location).attr('href'),
                parts = url.split("/"),
                last_part = parts[parts.length - 1];

            var name_form = $('.ajaxForm').data('name');

            if (isNaN(last_part) == false) {
                if (last_part != null) {
                    $('.title_info').html("Edit");
                    Render_Data(last_part);
                }
            } else {
                $('.title_info').html("Create new");
            }
        });

        function addRestaurant(e) {
            e.preventDefault();
            if ($('#name').val() == "" || $('#email').val() == "") {
                toastr.error('لا يوحد بيانات', 'العمليات');
                return;
            } else {
                var form = $('#form')[0];
                var data = new FormData(form);
                //route('dashboard_restaurant.post_data')
                $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: "<?php echo e(route('dashboard_restaurant.post_data')); ?>",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: (d) => {
                        console.log("dd:", d);
                        window.location.href = d.redirect;
                    },
                    error: (e) => {
                        console.log("fail:", e);
                    }
                });


                //$('#form').submit();
            }
        }


        var modal = document.getElementById('id01');
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function paymentOnline() {
            var checkedValue = $('#someSwitchOptionSuccess3:checked').val();
            if (checkedValue == "on") {
                modal.style.display = "block";
            }
        }
        var Render_Data = function(id) {
            $.ajax({
                url: "<?php echo e(route('dashboard_restaurant.get_data_by_id')); ?>",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function(result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#fees').val(result.success.fees);
                        $('#name').val(result.success.user.name);
                        $('.avatar_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.user.avatar);
                        $('.btn_re').html("Update Restaurant");
                        $('#phone').val(result.success.user.phone);
                        $('#email').val(result.success.user.email);
                        $('#sub_emails').val(result.success.user.sub_emails)
                        $('#delivery').val(result.success.delivery);

                        $('#password_confirmation').val(result.success.user.show_password);
                        $('#password').val(result.success.user.show_password);
                        
                        $("#landline_number").val(result.success.user.landline_number);
                        $("#opening_hours").val(result.success.user.opening_hours);
                        
                        $("#map_link").val(result.success.user.map_link);
                        //$("#longtitude").val(result.success.user.longtitude);

                        var image = geturlphoto() + result.success.user.avatar;

                        if (image != "") {
                            var editdrEvent_Img1 = $('#avatar').dropify({
                                defaultFile: 'data:image/jpeg;base64,' + image
                            });
                            editdrEvent_Img1 = editdrEvent_Img1.data('dropify');
                            editdrEvent_Img1.resetPreview();
                            editdrEvent_Img1.clearElement();
                            editdrEvent_Img1.settings.defaultFile = 'data:image/jpeg;base64,' + image;
                            editdrEvent_Img1.destroy();
                            editdrEvent_Img1.init();

                            $('.dropify-render').attr("src", image);
                            $('.dropify-render img').attr("src", image);
                        }

                        console.log("Showing category");

                        //category_id
                        var arry1 = [];
                        arry1.push(result.success.restaurant_category);
                        $('#category_id').multipleSelect('setSelects', arry1);
                        $("#category_id").val(arry1);
                        console.log("Showing city");
                        //city_id
                        var arry2 = [];
                        arry2.push(result.success.restaurant_city);
                        $('#city_id').multipleSelect('setSelects', arry2);
                        $("#city_id").val(arry2);
                        //someSwitchOptionSuccess3

                        if (result.success.cash == 1) {
                            $("#someSwitchOptionSuccess1").attr("checked", "checked")
                        }
                        if (result.success.visa == 1) {
                            $("#someSwitchOptionSuccess2").attr("checked", "checked")
                        }
                        if (result.success.online == 1) {
                            $("#someSwitchOptionSuccess3").attr("checked", "checked");
                        }
                        $('#sub_emails').selectize({
                            delimiter: ',',
                            persist: false,
                            create: function(input) {
                                return {
                                    value: input,
                                    text: input
                                }
                            }
                        });
                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "<?php echo e(route('dashboard_restaurant.index')); ?>";
                    }
                }
            });
        };
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mapstore/public_html/resources/views/dashboard/restaurant/add_edit.blade.php ENDPATH**/ ?>