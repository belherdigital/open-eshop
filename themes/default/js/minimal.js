$('#mini-tabs a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
});

$(function(){
alert($('iframe').contents().attr('height'));
$('iframe').contents().attr('height','60')
// window.frameElement.style.height = '60px';
    // alert($('iframe').contents().attr('style'));
    //Iframe payment butom customization
    $('iframe').contents().find('button').css({'height': '60px',
                                               'font-size': '19px',
                                               'padding': '6px',
                                               'border-radius': '5px',
                                               'font-family': '"Lato","Helvetica Neue",Helvetica,Arial,sans-serif'
                                            });

});
