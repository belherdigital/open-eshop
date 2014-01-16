    $('#review_raty').raty({ path: '/themes/default/img/raty',
                             scoreName  : 'rate',
                             score      : 5,
                             size       : 24 });

    $('#rated_raty').raty({ path: '/themes/default/img/raty',
                            readOnly: true,
                            score: function() {return $(this).attr('data-score');} ,
                            size       : 24,
                            
                          });