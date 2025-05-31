$(function () {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    if($.session.get('success')){
        toastr.success($.session.get('success'));
        $.session.remove('success')
    }
    
    if($.session.get('info')){
        toastr.info($.session.get('info'));
        $.session.remove('info')
    }
    
    if($.session.get('error')){
        toastr.error($.session.get('error'));
        $.session.remove('error')
    }
});