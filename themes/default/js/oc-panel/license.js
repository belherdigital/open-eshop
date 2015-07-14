$(function(){
    $('button.submit').click(function() {
        $button = $(this);
        $form = $(this).closest('form');
        swal ({
            title: $button.attr('title'),
            text: $button.data('text'),
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: $button.data('confirmButtonText'),
            cancelButtonText: $button.data('cancelButtonText'),
            allowOutsideClick: true,
        },
        function (confirmed){
            if (confirmed) {
                $form.submit();
            }
        });
    })
});