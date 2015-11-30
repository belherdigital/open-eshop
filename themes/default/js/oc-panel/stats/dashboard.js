// affiliate link generator
$('#affiliate_percentage').change(function(){
	var url = $('option:selected', this).data('url');
	var embed = $('option:selected', this).data('embed');
	var price = $('option:selected', this).data('price');
	//regenerate the simple link
	$( ".affi-example-link" ).html( '<a target="_blank" href="'+url+'">'+url+'</a>' );

	//regenerate txt area with modal/embed
	$('#embed_button').val("\<script src='"+embed+"'\>\</script\>\<a class='oe_button' href='"+url+"'>"+price+"\</a>");

	//regenerate txt area NO modal/embed
	$('#no_embed_button').val("\<a class='oe_button' href='"+url+"'>"+price+"\</a>");
});

if($('#from_date').length !== 0){
		$('#from_date').datepicker();}
if($('#to_date').length !== 0){
		$('#to_date').datepicker();}

$('#from_date').datepicker().on('changeDate', function(e){
	$('form[name="date"]').submit();
});
$('#to_date').datepicker().on('changeDate', function(e){
	$('form[name="date"]').submit();
});
