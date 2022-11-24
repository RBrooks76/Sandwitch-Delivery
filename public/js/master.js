var Render_Data = function () {

};

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

$(document).ready(function () {
    "use strict";
    //Code here.
    $('.PopUp').on("click",function () {
        $('.form-control').removeClass('border-danger');
        $('span.error').remove();
        $('.avatar_view').addClass('d-none');
    });

    $(document).on('submit', '.ajaxForm', function () {
        event.preventDefault(); //prevent default action
        var post_url = $(this).attr("action"); //get form action url
        var request_method = $(this).attr("method"); //get form GET/POST method
        var form_data = new FormData(this); //Encode form elements for submission
        var name = $(this).data('name');
        var current_data = $(this).serializeArray();
        var id_current_data = current_data[1]['value']
        var old_name_Su = $("."+ name +" .btn-load").html();
        $.ajax({
            url : post_url,
            type: request_method,
            data : form_data,
            beforeSend: function() {
                // setting a timeout
                $("."+ name +" .btn-load").html("Loading...");
                $("."+ name +" .btn-load").prop("disabled", true);
            },
            contentType: false,
            processData:false,
            xhr: function(){
                //upload Progress
                var xhr = $.ajaxSettings.xhr();
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', function(event) {
                        $("."+ name +" .btn").prop("disabled", true);
                    }, true);
                }
                return xhr;
            }
        }).done(function(data){
            $("."+ name +" .btn-load").html(old_name_Su);
            $("."+ name +" .btn-load").prop("disabled", false);
            if(data.message != null)
            {
                $("."+ name +" .btn").prop("disabled", false);
                $('.'+name+' .error').remove();
                toastr.error(data.message);
            }
            else if(data.errors != null)
            {
                $("."+ name +" .btn").prop("disabled", false);
                $('.'+name+' .error').remove();
                $('.'+name+' .form-control').removeClass('border-danger');
                $.each(data.errors ,function (index,val) {
                    toastr.error(val);
                    $('.'+name+' #'+index).addClass('border-danger');
                    $('.'+name+' #'+index).after('<span class="error" id="error_'+ index +'" style="color: red">'+val+'</span>');
                });
            }
            else if (data.error != null){
                $("."+ name +" .btn").prop("disabled", false);
                toastr.error(data.error);
                if(data.redirect != null){
                    window.location.href = data.redirect;
                }
            }
            else if(data.redirect != null){
                window.location.href = data.redirect;
            }
            else
            {
                toastr.success(data.success);
                $('.'+name+' .error').remove();
                $('.'+name+' .form-control').removeClass('border-danger');
                $("."+ name +" .btn").prop("disabled", false);
                $('.form-control').val('');
                $('.sub').val('');
                if(data.url != null){
                    window.setTimeout(function(){
                        window.location.href = data.url;
                    }, 2000);
                }
                if(data.cart == 1){
                    Cart();
                }
                if(data.dashboard == '1'){
                    if(data.same_page == '1'){
                        Render_Data();
                    }
                    else if(data.close == 1){
                        $('#data_Table').DataTable().ajax.reload(null, false);
                        $(".modal").modal('hide');
                    }
                    else{
                        $('#data_Table').DataTable().ajax.reload(null, false);
                        if(id_current_data){
                            $('#data_Table tbody #' + id_current_data).css('background','hsla(64, 100%, 50%, 0.36)');
                        }
                        else{
                            $('#data_Table tbody tr').css('background','transparent');
                        }
                    }
                    if(data.note == 1){
                        //$('.sumernote').summernote('code','');
                        $('#summary').froalaEditor('html.set', '');
                    }
                }
                if(data.redirect != null){
                    window.location.href = data.redirect;
                }
                $('.box_img').addClass('d-none');
                $(".modal").modal('hide');
                $(".error_f").html('');
                $(".cls").val('');
            }
        })
        .fail(function(xhr, status, error){
            $("."+ name +" .btn").prop("disabled", false);
            $('.'+name+' .error').remove();
            toastr.error(xhr);
            toastr.error(status);
            toastr.error(error);
        });
    });

});
