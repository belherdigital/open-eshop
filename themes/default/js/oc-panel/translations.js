$(function(){

    $('.button-copy').click(function(event) {
        event.preventDefault();          
        var orig = $(this).data('orig');
        var dest = $(this).data('dest');
        $('#'+dest).val($('#'+orig).val());
        $('#'+$(this).data('tr')).removeClass('').addClass('warning');
    });

    $('#button-copy-all').click(function(event) {
        event.preventDefault();   
        if (confirm($(this).data('text')))
        {   
            $('.button-copy').each(function() {
                var orig = $(this).data('orig');
                var dest = $(this).data('dest');
                $('#'+dest).val($('#'+orig).val());
                $('#'+$(this).data('tr')).removeClass('').addClass('warning');
            });
        }
    });

    $('.button-translate').click(function(event) {
        event.preventDefault();          
        var orig = $(this).data('orig');
        var dest = $(this).data('dest');
        var transData = $('#button-translate-all');
        $('#'+$(this).data('tr')).removeClass('').addClass('warning');
        translate(transData.data('apikey'),transData.data('langsource'),transData.data('langtarget'),$('#'+orig).val(), dest);         
    });

    $('#button-translate-all').click(function(event) {
        event.preventDefault();       
        var transData = $('#button-translate-all');
        if (confirm($(this).data('text')))
        { 
            $('.button-translate').each(function() {
                var orig = $(this).data('orig');
                var dest = $(this).data('dest');
                $('#'+$(this).data('tr')).removeClass('').addClass('warning');
                translate(transData.data('apikey'),transData.data('langsource'),transData.data('langtarget'),$('#'+orig).val(), dest);
            });  
        }  
    });

});


function translate(apiKey, langSource, langTarget, text, destination)
{
    var apiurl = 'https://www.googleapis.com/language/translate/v2?key=' + apiKey + '&source=' + langSource + '&target=' + langTarget + '&q=';

    $.ajax({
        url: apiurl + encodeURIComponent(text),
        dataType: 'jsonp',
        success: function(data) {
             $('#'+destination).val(data.data.translations[0].translatedText);
            
        }
    });
}

