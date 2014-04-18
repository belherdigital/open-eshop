$(document).ready(function() {
    var trigger = false;
    var panels = $('.user-infos');
    
    //Click dropdown
    $('.dropdown-user').click(function() {
        //get data-for attribute
        var dataFor = $(this).attr('data-for');
        //current button
        var currentButton = $(this);
        $(dataFor).slideToggle(0, function() {
            //Completed slidetoggle
            if($(this).is(':visible'))
            {
                currentButton.html('<i class="glyphicon glyphicon-chevron-up"></i>');
                $('.short-text', this).hide();
                $('.long-text', this).show();
            }
            else
            {
                currentButton.html('<i class="glyphicon glyphicon-chevron-down"></i>');
                $('.short-text', this).show();
                $('.long-text', this).hide();
            }
        })
    });

    //when loading collaps all, except last one 
    $(panels).each(function(index, element){
        $(this).hide();
        if(index == panels.length -1){
            $('.dropdown-user').each(function(i, e){
                if(i == $('.dropdown-user').length -1){
                    $(this).trigger("click");  
                }
            });
        }    
    });

    //collapse all button
    $('#collapse-all-tickets').on('click',function(){
        $('.user-ticket').each(function(){
            if(!trigger)
            {
                $('.short-text').hide();
                $('.long-text').show();

            }
            else
            {
                $('.short-text').show();
                $('.long-text').hide();
            }
        });
        if(trigger)
            trigger = false;
        else
            trigger = true;
    });
    $('[data-toggle="tooltip"]').tooltip();
});