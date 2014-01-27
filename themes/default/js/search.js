showCustomFieldsByCategory(this);

$( "#category" ).change(function() {
	showCustomFieldsByCategory(this);
});


function showCustomFieldsByCategory(element){

	id_categ = $(":selected" ,element).attr('data-id');
  	$(".data-custom").each(function(){
  	field = $(this);
	dataCategories = field.attr('data-categories');
	
	if(dataCategories)
    {
        // show if cf fields if they dont have categories set
        if(dataCategories.length != 2){
            field.closest('.form-group').css('display','none');
            // field.prop('disabled', true);
        }
        else{
            field.closest('.form-group').css('display','inline-block');
            // field.prop('disabled', false);
            
        }
        if(dataCategories !== undefined)  
        {   
            if(dataCategories != "")
            {
                // apply if they have equal id_category 
                $.each($.parseJSON(dataCategories), function (index, value) { 
                    if(id_categ == value){
                        field.closest('.form-group').css('display','inline-block');
                        // field.prop('disabled', false);
                        
                    }
                });
            }
        }
    }
   	});
}

// $('form').each()