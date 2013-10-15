$(function(){
	$('.index-delete').click(function(event) {
		  event.preventDefault();
		  $this = $(this);
		  if (confirm($this.data('text')))
		  {
			  $.ajax({ url: $this.attr('href'),
				}).done(function ( data ) {
					$('#'+$this.data('id')).hide("slow");
				});
		  }
	});
});