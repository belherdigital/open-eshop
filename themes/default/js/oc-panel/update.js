$(function(){
    $(".confirm-button").click(function(event) {
        var href = $(this).attr('href');
        var redirect = $(this).attr('redirect');
        var title = $(this).attr('title');
        var text = $(this).data('text');
        var confirmButtonText = $(this).data('btnoklabel');
        var cancelButtonText = $(this).data('btncancellabel');
        event.preventDefault();
        swal({
            title: title,
            text: text,
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            allowOutsideClick: true,
        },
        function(){
            $('#processing-modal').modal('show');
            window.location.href = href;
        });
    }); 
});
