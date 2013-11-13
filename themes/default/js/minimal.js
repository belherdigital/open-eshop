$('#mini-tabs a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
});

$(function(){

    //Iframe payment butom customization
    $('iframe').contents().find('button').css({'height': '40px',
                                               'font-size': '19px',
                                               'padding': '6px',
                                               'border-radius': '5px',
                                               'font-family': '"Lato","Helvetica Neue",Helvetica,Arial,sans-serif'
                                            });

});
