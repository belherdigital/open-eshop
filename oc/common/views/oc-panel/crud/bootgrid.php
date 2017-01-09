var grid = $("#grid-data-api").bootgrid({
    ajax: true,
    url: $('#filter_buttons').data('url'),
    rowCount: [10,25,50,100],
    <?if(count($search_fields) > 0):?>
    searchSettings: {
        delay: 500,
        characters: 3
    },
    <?endif?>   
    formatters: {
        "commands": function(column, row)
        {
            edit_button = "<?if ($controller->allowed_crud_action('update')):?><a href=\"<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'update'))?>/"+ row.<?=$element->primary_key()?> +"\" class=\"btn btn-primary ajax-load command-edit\" data-row-id=\"" + row.<?=$element->primary_key()?> + "\"><span class=\"fa fa-pencil\"></span></a><?endif?>";
            dele_button = "<?if ($controller->allowed_crud_action('delete')):?><a href=\"<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'delete'))?>/"+ row.<?=$element->primary_key()?> +"\" class=\"btn btn-danger command-delete\" data-row-id=\"" + row.<?=$element->primary_key()?> + "\" title=\"<?=__('Are you sure you want to delete?')?>\" data-btnOkLabel=\"<?=__('Yes, definitely!')?>\" data-btnCancelLabel=\"<?=__('No way!')?>\"><span class=\"fa fa-trash-o\"></span></a><?endif?>";
            extra_button = "<?if (count($buttons) > 0):?><div class=\"btn-group\"><button type=\"button\" class=\"btn btn-default dropdown-toggle extra-commands\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fa fa-cog\"></i></button><ul class=\"dropdown-menu dropdown-menu-right\"><?
            foreach($buttons as $button):?><li><a href=\"<?=$button['url']?>"+ row.<?=$element->primary_key()?> +"\" class=\"<?=$button['class']?>\" data-row-id=\"" + row.<?=$element->primary_key()?> + "\" title=\"<?=$button['title']?>\" <?=isset($button['attrs']) ? addslashes(HTML::attributes($button['attrs'])) : NULL?>><i class=\"<?=$button['icon']?>\"></i> <?=$button['title']?></a></li><?endforeach?></ul></div><?endif?>";
            return '<div class="btn-group" style="display: flex;">'+edit_button+dele_button+extra_button+'</div>';    
        }
    }
})
.on("loaded.rs.jquery.bootgrid", function()
{
    /* Executes after data is loaded and rendered */
    grid.find(".command-delete, [data-toggle='confirmation']").on("click", function(event)
    {
        var href = $(this).attr('href');
        var title = $(this).attr('title');
        var text = $(this).data('text');
        var id = $(this).data('row-id');
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
                    $("#grid-data-api").bootgrid("reload");
            });
        });
    });

    $(".dropdown-toggle.extra-commands").dropdown();

    <?if(count($search_fields) == 0):?>
    $('.search.form-group').html('');
    <?endif?>   

    $("#grid-data-api tr").removeClass( "success info" )
    //filter_buttons = $('#filter_buttons').html();
    //$('#filter_buttons').html('');
    //$('.actionBar').html(filter_buttons+$('.actionBar').html());
/*    $( '#form-ajax-load').submit(function( event ) {
        event.preventDefault();
        form = $(this);
        pageurl = form.attr('action')+'?'+form.serialize();
        $('#filter_buttons').data('url',pageurl);
        if ( history.replaceState ) history.pushState( {}, document.title, pageurl );
        $("#grid-data-api").bootgrid("reload");
    });*/

});


//form search
$( '#form-ajax-load').submit(function( event ) {
    event.preventDefault();
    $("html,body").scrollTop(0);
    form = $(this);
    //get the url of the form
    pageurl = form.attr('action')+'?'+form.serialize();
    form.css('cursor','wait');
    //to get the ajax content and display in div with id 'content'
    $.ajax({
        url:updateURLParameter(pageurl,'rel','ajax'),
        beforeSend: function() {
                                    if ($("#loadingbar").length === 0) {
                                        $("body").append("<div id='loadingbar'></div>")
                                        $("#loadingbar").addClass("waiting").append($("<dt/><dd/>"));
                                        $("#loadingbar").width((50 + Math.random() * 30) + "%");
                                    }
                                }
                                }).always(function() {
                                    $("#loadingbar").width("101%").delay(200).fadeOut(400, function() {
                                    $(this).remove();});
                                }).done(function(data) {
                                    if ( history.replaceState ) history.pushState( {}, document.title, pageurl );
                                    form.css('cursor','');

                                    if(document.getElementById('page-wrapper') == null) {
                                        $("#content").html(data);
                                    } 
                                    else {
                                        $("#page-wrapper").html(data);
                                    }
                                    
                                    init_panel();});

    return false;  
});

$('.datepicker_boot').datepicker();