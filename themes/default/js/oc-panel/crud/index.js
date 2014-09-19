$(function(){
    $('.index-delete').confirmation({
        onConfirm: function(event, element) {
            event.preventDefault();
            $.ajax({ url: $(element).attr('href'),
                }).done(function ( data ) {
                    $('#'+$(element).data('id')).hide("slow");
            });
        }
    });
});
