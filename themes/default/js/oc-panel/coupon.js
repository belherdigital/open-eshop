$(function(){
    $(".index-delete").click(function(event) {
        var href = $(this).attr('href');
        var title = $(this).attr('title');
        var text = $(this).data('text');
        var id = $(this).data('id');
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
            $.ajax({ url: href,
                }).done(function ( data ) {
                    $('#'+id).hide("slow");
            });
        });
    }); 
    
    if($('[data-toggle="datepicker"]').length !== 0){
        $('[data-toggle="datepicker"]').datepicker();
    }
    
    $('.btn-fixed').click(function(event) {
        event.preventDefault();
        $(this).addClass('active');
        $('.btn-percentage').removeClass('active');
        $('input[name="discount_percentage"]').closest('.form-group ').addClass('hidden');
        $('input[name="discount_amount"]').closest('.form-group ').removeClass('hidden');
    });
    
    $('.btn-percentage').click(function(event) {
        event.preventDefault();
        $(this).addClass('active');
        $(".btn-fixed").removeClass('active');
        $('input[name="discount_amount"]').closest('.form-group ').addClass('hidden');
        $('input[name="discount_percentage"]').closest('.form-group ').removeClass('hidden');
    });
});
