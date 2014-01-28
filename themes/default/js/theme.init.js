$(function(){
    if(!$("select").hasClass('disable-chosen')){
        $("select").chosen();   
    } 
    $('.remove_chzn').chosen('destroy');

    // $( "div.sceditor-group" ).css('padding','1px 15px 5px 5px');
    
    $("a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_rounded',slideshow:3000, autoplay_slideshow: false});
 
    $('input, select, textarea, .btn').tooltip();

    $('.radio > input:checked').parentsUntil('div .accordion').addClass('in');

    $("#slider-fixed-products").carousel({ interval: 5000 });

    $(window).load(function(){
        $('#accept_terms_modal').modal('show');
    });
    if (!navigator.onLine) $('.off-line').show();
    

    // fix sub nav on scroll
    var $win = $(window)
      , $nav = $('.subnav')
      , navHeight = $('.navbar').first().height()
      , navTop = $('.subnav').length && $('.subnav').offset().top - navHeight
      , isFixed = 0

    processScroll();

    $win.on('scroll', processScroll);

    function processScroll() {
      var i, scrollTop = $win.scrollTop()
      if (scrollTop >= navTop && !isFixed) {
        isFixed = 1
        $nav.addClass('subnav-fixed')
      } else if (scrollTop <= navTop && isFixed) {
        isFixed = 0
        $nav.removeClass('subnav-fixed')
      }
    }

});

function setCookie(c_name,value,exdays)
{
var exdate = new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value = escape(value) + ((exdays==null) ? "" : ";path=/; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
}