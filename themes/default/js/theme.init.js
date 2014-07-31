$(function(){
    if(!$("select").hasClass('disable-chosen')){
        $("select").chosen({
          no_results_text: getChosenLocalization("no_results_text"),
          placeholder_text_multiple: getChosenLocalization("placeholder_text_multiple"),
          placeholder_text_single: getChosenLocalization("placeholder_text_single")
        });   
    } 
    $('.remove_chzn').chosen('destroy');

    // $( "div.sceditor-group" ).css('padding','1px 15px 5px 5px');
    
    $("a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_rounded',slideshow:3000, autoplay_slideshow: false});
 
    $('input, select, textarea, .btn , img').tooltip();

    $('.radio > input:checked').parentsUntil('div .accordion').addClass('in');

    $("#slider-fixed-products").carousel({ interval: 5000 });

    $(window).load(function(){
        $('#accept_terms_modal').modal('show');
    });
    
    //online offline message
    window.addEventListener("offline", function(e) {
        $('.off-line').show();
    }, false);

    window.addEventListener("online", function(e) {
        $('.off-line').hide();
    }, false);
    
    //list / grit swap
    
  $('#list').click(function(event){
    event.preventDefault();
    $('#products .item').addClass('list-group-item');
    $(this).addClass('active');
    $('#grid').removeClass('active');
    
    //text update if grid
    $('.big-txt').removeClass('hide');
    $('.small-txt').addClass('hide');
    setCookie('list/grid',1,10);
  });

  $('#grid').click(function(event){
    event.preventDefault();
    $('#products .item').removeClass('list-group-item');
    $('#products .item').addClass('grid-group-item');
    $(this).addClass('active');
    $('#list').removeClass('active');
    
    //text update if grid
    $('.small-txt').removeClass('hide');
    $('.big-txt').addClass('hide');
    setCookie('list/grid',0,10);
  });

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

//chosen fix in authorize modal
  $('#authorize_modal').on('shown.bs.modal', function () {
      $('select', this).chosen('destroy').chosen({
		  	no_results_text: getChosenLocalization("no_results_text"),
		  	placeholder_text_multiple: getChosenLocalization("placeholder_text_multiple"),
		  	placeholder_text_single: getChosenLocalization("placeholder_text_single")
		  });
  });

//validate authorize form
  $.validator.setDefaults({ ignore: ":hidden:not(select)" });
  $(".authorize_form").validate();

//widget_reviews slider
  $('.btn-vertical-slider').on('click', function () {
        
      if ($(this).attr('data-slide') == 'next') {
          $('#myCarousel').carousel('next');
      }
      if ($(this).attr('data-slide') == 'prev') {
          $('#myCarousel').carousel('prev')
      }

  });
function setCookie(c_name,value,exdays)
{
var exdate = new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value = escape(value) + ((exdays==null) ? "" : ";path=/; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
}
function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}