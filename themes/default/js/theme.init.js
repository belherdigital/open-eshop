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

//validate auth pages
$(function(){

    var $params = {rules:{}, messages:{}};
    $params['rules']['email'] = {required: true, email: true};

    $(".auth").each(function() {
        $(this).validate($params)
    });

    var $register_params = {rules:{}, messages:{}};
    $register_params['rules']['email'] = {required: true, email: true};
    $register_params['rules']['password1'] = {required: true};
    $register_params['rules']['password2'] = {required: true};

    $(".register").each(function() {
        $(this).validate($register_params)
    });

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

var savedRate, savedCurrency, siteCurrency;
siteCurrency = getlocale();
savedCurrency = getCookie('site_currency');
if (savedCurrency == undefined) {
    savedRate = 1;
    savedCurrency = siteCurrency;
}else {
    savedRate = getCookie('site_rate');
    savedCurrency = getCookie('site_currency');
    rate = parseFloat(savedRate);
    var prices = $('.price-curry'), money;
    prices.each(function(){
      money = $(this).text();
      money = Number(money.replace(/[^0-9\.]+/g, ''));
      converted = rate * money;
      var symbols = ({
          'USD': '&dollar;',
          'GBP': '&pound;',
          'EUR': '&euro;',
          'JPY': '&yen;'
        });
        converted = Number(converted.toString().match(/^\d+(?:\.\d{2})?/));
        symbol = symbols[savedCurrency] || savedCurrency;
        $(this).text($(this).html(symbol + ' ' + converted).text());
    });
 }

$(function(){
 if ($('.curry').length){
     $('.my-future-ddm').curry({
        change: true,
        target: '.price-curry',
        base: savedCurrency == undefined?siteCurrency:savedCurrency,
        symbols: {}
     }).change(function(){
        var selected = $(this).find(':selected'), // get selected currency
        currency = selected.val(); // get currency name
      
        getRate(siteCurrency, currency);
        setCookie('site_currency', currency, { expires: 7, path: '' });

     });
 }
});

function getRate(from, to) {
    var script = document.createElement('script');
    script.setAttribute('src', "http://query.yahooapis.com/v1/public/yql?q=select%20rate%2Cname%20from%20csv%20where%20url%3D'http%3A%2F%2Fdownload.finance.yahoo.com%2Fd%2Fquotes%3Fs%3D"+from+to+"%253DX%26f%3Dl1n'%20and%20columns%3D'rate%2Cname'&format=json&callback=parseExchangeRate");
    document.body.appendChild(script);
}

function parseExchangeRate(data) {
    var name = data.query.results.row.name;
    var rate = parseFloat(data.query.results.row.rate, 10);
    //console.log(rate);
    setCookie('site_rate', rate, { expires: 7, path: '' });
}

$(document).ready(function() {
  $('.selectpicker').selectpicker({
    style: 'btn-default',
    size: false
  });
});