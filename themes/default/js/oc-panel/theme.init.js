$(function (){
    

    if ($("textarea[name=description]").data('editor')=='html')
    {
        $("#formorm_description, textarea[name=description], textarea[name=email_purchase_notes], .cf_textarea_fields").addClass('span8').sceditor({
            plugins: "xhtml",
            height: "450",
            toolbarExclude: "emoticon,cut,copy,paste,pastetext",
            resizeEnabled: "true",
            emoticonsEnabled: "false",
            emoticonsCompat: "false",
            enablePasteFiltering: "true"
        });
    }
    else
    {
        $('#formorm_description, textarea[name=description], textarea[name=email_purchase_notes], .cf_textarea_fields').addClass('span6').sceditorBBCodePlugin({
            toolbar: "bold,italic,underline,strike|left,center,right,justify|" +
            "bulletlist,orderedlist|link,unlink,image,youtube|source",
            resizeEnabled: "true",
            emoticonsEnabled: "false",
            emoticonsCompat: "false",
            enablePasteFiltering: "true"});
    }
    

    $('.tips').popover();

    $("select").chosen();
    
    $('.radio > input:checked').parentsUntil('div .accordion').addClass('in');
    
    $('select[name="locale_select"]').change(function()
    {
         $('#locale_form').submit();
    });
    $('select[name="type"]').change(function()
    {
        // alert($(this).val());
        if($(this).val() == 'email') 
            $('#from_email').parent().parent().css('display','block');
        else
            $('#from_email').parent().parent().css('display','none');
    });

    $('input').each(function(){
        if(!$('input').hasClass('form-control') && 
        $('input').attr('type') != 'checkbox') {$('input').addClass('form-control');}
    });
});

_debounce = function(func, wait, immediate) {
    var timeout, result;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) result = func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) result = func.apply(context, args);
        return result;
    };
};

function setCookie(c_name,value,exdays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays==null) ? "" : ";path=/; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}