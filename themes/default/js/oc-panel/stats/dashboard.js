
if($('#from_date').length != 0){
        $('#from_date').datepicker();}
if($('#to_date').length != 0){
        $('#to_date').datepicker();}

$('#statsTabs a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})