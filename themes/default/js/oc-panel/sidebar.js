
//go to the latest tab, if it exists:
var collapsed_bar = localStorage.getItem('sidebar_state');

/*Side bar colapse*/
if($(window).width() > '750'){
  /*Bigger screens*/
  if($(window).width() < '1200'){ // when less than 1200px, do automatically small sidebar
    colapse_sidebar(true);
  }
  // on click triger
  $('.btn-colapse-sidebar').on('click', function(){
    colapse_sidebar($('.nav.nav-list.side-ul').hasClass('active'));
  });

  if(collapsed_bar == 'collapsed')
    colapse_sidebar(true);
  else
    colapse_sidebar(false);
}else{
  /*Mobile case*/
  // $('.btn-colapse-sidebar').parent().css('display','none'); // hide collapse button since it doesnt work here
  var sidebar = $('.respon-left-panel');
  var main_content = $('.main');
  sidebar.addClass('hide'); // when mobile always hide
  $('#mobile_header_btn, .btn-colapse-sidebar').on('click', function(){
    if(sidebar.hasClass('hide')){
      sidebar.removeClass('hide');
      main_content.css('margin-left','200px');
    }
    else{
      sidebar.addClass('hide');
      main_content.css('margin-left','auto');
    }
  });
  
}
/*
  Colapse sidebar function
  makes sidebar to mini sidear with only icons active
*/

function colapse_sidebar(event){  
// $('.submenu.active > .side-name-link').removeClass('hide');
  if(event)
  {
    //set localstorage to be avare of current state of sidebar
    localStorage.setItem('sidebar_state', 'collapsed');

    $('.nav.nav-list.side-ul li').each(function(){
      $('a span.side-name-link', this).removeClass('active').addClass('hide'); // hide links in sidebar
      $('i', this).addClass('pos'); // remove class with padding
    });
    $('.nav.nav-list.side-ul').removeClass('active').addClass('colapsed');
    $('.nav.nav-list.side-ul').closest('aside').addClass('respones-colapse');
    $('.dropdown-sidebar.sbp').addClass('mini-col');
    $('.main').css('padding-left','50px');
    $('.no-prem').hide(); // hide adverts
    
    $('.btn-colapse-sidebar i')
      .removeClass('glyphicon-circle-arrow-left')
      .addClass('glyphicon-circle-arrow-right');

    $('.dropdown-sidebar.sbp.active .submenu').removeClass('active'); // in case its mini, do not colapse menu item
    
  }
  else
  {
    //set localstorage to be avare of current state of sidebar
    localStorage.setItem('sidebar_state', 'not-collapsed');

    $('.nav.nav-list.side-ul li').each(function(){
      $('a span.side-name-link', this).removeClass('hide').addClass('active');
      $('i', this).removeClass('pos'); // remove class with padding
    });

    $('.nav.nav-list.side-ul').removeClass('colapsed').addClass('active');
    $('.nav.nav-list.side-ul').closest('aside').removeClass('respones-colapse');
    $('.dropdown-sidebar.sbp').removeClass('mini-col');
    $('.main').css('padding-left','205px');
    $('.no-prem').show(); // show adverts
    
    $('.btn-colapse-sidebar i')
      .removeClass('glyphicon-circle-arrow-right')
      .addClass('glyphicon-circle-arrow-left');

    $('.dropdown-sidebar.sbp.active .submenu').addClass('active'); // in case its maximized, collapse menu item
    
  }
}

$(function() {
  if(!$('.dropdown-sidebar').hasClass('mini-col')){
    $('.dropdown-sidebar.sbp.active .submenu').addClass('active');
  }
  $('.dropdown-sidebar.sbp.active .dropdown-toggle .glyphicon-chevron-down')
      .removeClass('glyphicon-chevron-down')
      .addClass('glyphicon-chevron-up');
    
    $('.dropdown-sidebar').hover(function(){
      if($(this).hasClass('mini-col') || !$('.submenu li', this).hasClass('active')){
        dropdown($(this));
      }
    });
  
  

  
});

function dropdown(event){
  var active = $('.submenu',event);

  if(active.hasClass('active'))
  {
    active.removeClass('active');
    $('.submenu .side-name-link',event).addClass('hide');
  }
  else
  {
    active.addClass('active');
    $('.submenu .side-name-link',event).removeClass('hide');
  }
}

// position: absolute;top: 7px;left: 21px;border: 1px solid;padding: 4px 20px 3px 2px;margin-left: 47px;
