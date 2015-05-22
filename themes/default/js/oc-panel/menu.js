$(function  () {
    var group = $("ol.plholder").sortable({
        group: 'plholder',
        onDrop: function (item, container, _super) {
            //first we execute the normal plugins behaviour
            _super(item, container);

            //where we drop the category
            var parent = $(item).parent();

            //values of the list
            val = $(parent).sortable().sortable('serialize').get();

            //empty UL
            if (val == '[object HTMLUListElement]') {
                val = '';
            }
            else{
                //array of values
                val = val[0].split(',');
            }
            
            //generating the array to send to the server
            var data = {};
            data['order'] = val;

            //saving the order
            $.ajax({
                type: "GET",
                url: $('#ajax_result').data('url'),
                beforeSend: function(text) {
                    $('#ajax_result').text('Saving').removeClass().addClass("label label-warning");
                    $("ol.plholder").sortable('disable');
                    $('ol.plholder').animate({opacity: '0.5'});
                },
                data: data,
                success: function(text) {
                    $('#ajax_result').text(text).removeClass().addClass("label label-success");
                    $("ol.plholder").sortable('enable');
                    $('ol.plholder').animate({opacity: '1'});
                }               
            });
        
             
        },
        serialize: function (parent, children, isContainer) {
             return isContainer ? children.join() : parent.attr("id");
        },

    })
})

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
});

$(function(){
    var new_url;
    var icons;
    var title;
    $('.menu_category').on('click',function()
    {
        //select radio if its notselected
        if(!$(this).parent().hasClass('in')){
            $(".in").removeClass('in').children().removeAttr('checked');
            $(this).attr('checked');
            $(this).parent().addClass('in');
        }
        new_url = "http://" + window.location.hostname +"/"+ $(this).attr('id').replace('radio_','');
        title = $(this).attr('data-name').replace('radio_','');
        $('input[name=title]').val(title);
        $('input[name=url]').val(new_url);
    });
    
    $('.default_links').on('click',function()
    {   
        //select radio if its notselected
        if(!$(this).parent().hasClass('in')){
            $(".in").removeClass('in').children().removeAttr('checked');
            $(this).attr('checked');
            $(this).parent().addClass('in');
        }

        // add values to forms
        new_url = "http://" + window.location.hostname +"/" + $(this).attr('data-url');
        icons = $(this).attr('data-icon');
        title = $(this).attr('id').replace('radio_','');
        $('input[name=title]').val(title);
        $('input[name=url]').val(new_url);
        $('input[name=icon]').val(icons);
    });

    $('#menu_type li a').on('click', function(){
        
        if($(this).hasClass('categories')){
            // $('#url').attr('disabled','disabled');
            $('#default-group').css('display','none');
            $('#categories-group').css('display','block');

        }
        else if($(this).hasClass('custom')){
            // $('#url').removeAttr('disabled','disabled');
            $('#default-group').css('display','none');
            $('#categories-group').css('display','none');
        }
        else if($(this).hasClass('default')){
            $('#categories-group').css('display','none');
            $('#default-group').css('display','block');
            // $('#url').removeAttr('disabled','disabled');
        }
    });

});