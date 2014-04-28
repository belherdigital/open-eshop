$(document).ready(function() {
    var trigger = false;
    var panels = $('.user-infos');
    
    $('div.user-ticket').click(function(){
        $('.dropdown-user', this);
        var dataFor = $('.dropdown-user', this).attr('data-for');
        //current button
        var currentButton = $('.dropdown-user', this);
        
        //Completed slidetoggle
        if($(this).is(':visible'))
        {
            currentButton.html('<i class="glyphicon glyphicon-chevron-up"></i>');
            $('.short-text', this).hide();
            $('.long-text', this).show();
            $(this).css('cursor','auto')
        }
        else
        {
            currentButton.html('<i class="glyphicon glyphicon-chevron-down"></i>');
            $('.short-text', this).show();
            $('.long-text', this).hide();
        }
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
                $('#collapse-all-tickets i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');

            }
            else
            {
                $('.short-text').show();
                $('.long-text').hide();
                $('#collapse-all-tickets i').addClass('glyphicon-chevron-down').removeClass('glyphicon-chevron-up');
            }
        });
        if(trigger)
            trigger = false;
        else
            trigger = true;
    });
    $('[data-toggle="tooltip"]').tooltip();
});
    