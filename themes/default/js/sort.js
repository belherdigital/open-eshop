var glyphicon_list = "<span class='glyphicon glyphicon-list-alt'></span>";
var caret = "<span class='caret'></span>";
$('#sort-list li').each(function(){
  var replace_text = $('a', this).text();
  var href_text = $('a', this).attr('href').replace('?sort=','');
  if($('#sort').attr('data-sort') == href_text){
    $('#sort').html(glyphicon_list+replace_text+caret);
  }
});