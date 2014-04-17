
if($('#from_date').length != 0){
        $('#from_date').datepicker();}
if($('#to_date').length != 0){
        $('#to_date').datepicker();}

$('#statsTabs a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
});
// after loading page, make sales active
$(window).load(function(){
	$('.tab-content').ready(function(){
		$('.tab-pane').each(function(){
			if($(this).attr('id') != 'sales'){
				$(this).removeClass('active');
			}
		});
	});
});