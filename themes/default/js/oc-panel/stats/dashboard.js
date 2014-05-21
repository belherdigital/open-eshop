
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
		$('#statsTabs').removeClass('invisible');
		$('.tab-pane').each(function(){
			if($(this).attr('id') != 'sales'){
				$(this).removeClass('active');
			}
		});
	});
});

// affiliate link generator
$('#affiliate_percentage').change(function(){
	var url = $('option:selected', this).data('url');
	$( ".affi-example-link" ).html( '<a target="_blank" href="'+url+'">'+url+'</a>' )
});