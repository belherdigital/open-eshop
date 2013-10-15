/**
 * [MODERATION / ADVERT. selectbox script]
 * 
 */
var href = $('.sel_url_to_redirect').attr('href');

var last_str = href.substr(href.lastIndexOf('/') );

var url_array = {"del"			:{'href':$("a.delete").attr("href")},
				 "spam"			:{'href':$("a.spam").attr("href")},
				 "deactivate"	:{'href':$("a.deactivate").attr("href")},
				 "activate"		:{'href':$("a.activate").attr("href")},
				 "featured"		:{'href':$("a.featured").attr("href")},
				 "deact_feature":{'href':$("a.featured").attr("href")}};

// selected checkboxes get new class
var selected = '';
$('input.checkbox').click(function(){
	if($(this).is(':checked')){
		$(this).addClass("selected");
		
		//loop to colect all id-s for checked advert-s
		selected = '';
		$('input.selected').each(function(){
			selected += ($(this).attr('id'));
		});
		
		selected = selected.replace(/_([^_]*)$/,'$1'); // reqex to remove last underscore

		//append new href with id-s, and check if it exists (.length ?)
		$('a.delete').length ? $('a.delete').attr('href', url_array['del']['href']+"/"+selected+last_str) : '';
		$('a.spam').length ? $('a.spam').attr('href', url_array['spam']['href']+"/"+selected+last_str) : '';
		$('a.deactivate').length ? $('a.deactivate').attr('href', url_array['deactivate']['href']+"/"+selected+last_str) : '';
		$('a.activate').length ? $('a.activate').attr('href', url_array['activate']['href']+"/"+selected+last_str) : '';
		$('a.featured').length ? $('a.featured').attr('href', url_array['featured']['href']+"/"+selected+last_str) : '';
		$('a.featured').length ? $('a.featured').attr('href', url_array['deact_feature']['href']+"/"+selected+last_str) : '';
	}else{

		$(this).removeClass("selected");

		selected = '';
		$('input.selected').each(function(){
			selected += ($(this).attr('id'));
		});

		// back to original href
		$('a.spam').attr('href', "/oc-panel/ad/spam");
		$('a.deactivate').attr('href', "/oc-panel/ad/deactivate");
		$('a.delete').attr('href', "/oc-panel/ad/delete");
		$('a.activate').attr('href', url_array['activate']['href']+'/'+selected);
		$('a.featured').attr('href', "/oc-panel/ad/featured");
	}
});


//select all check boxes and append class to all
function check_all(){
	var selected = '';

	if($('#select-all').is(':checked')){

		$('input.checkbox').addClass('selected').prop('checked', true);

		// get all selected and build string with id-s
		$('input.selected').each(function(){
			selected += ($(this).attr('id'));
		});
		
		selected = selected.replace(/_([^_]*)$/,'$1'); // reqex to remove last underscore 
		
		// for each button we generate route (url), that is later parsed and dealt accordingly 
		$('a.delete').length ? $('a.delete').attr('href', url_array['del']['href']+"/"+selected+last_str) : '';
		$('a.spam').length ? $('a.spam').attr('href', url_array['spam']['href']+"/"+selected+last_str) : '';
		$('a.deactivate').length ? $('a.deactivate').attr('href', url_array['deactivate']['href']+"/"+selected+last_str) : '';
		$('a.activate').length ? $('a.activate').attr('href', url_array['activate']['href']+"/"+selected+last_str) : '';
		$('a.featured').length ? $('a.featured').attr('href', url_array['featured']['href']+"/"+selected+last_str) : '';
		$('a.featured').length ? $('a.featured').attr('href', url_array['deact_feature']['href']+"/"+selected+last_str) : '';
	}else{
		selected = '';
		$('input.checkbox').removeClass('selected').attr('checked', false);
		$('a.spam').attr('href', url_array['spam']['href']+'/'+selected);
		$('a.deactivate').attr('href', url_array['deactivate']['href']+'/'+selected);
		$('a.delete').attr('href', url_array['del']['href']+'/'+selected);
		$('a.activate').attr('href', url_array['activate']['href']+'/'+selected);
		$('a.featured').attr('href', url_array['featured']['href']+'/'+selected);
	}

	
}