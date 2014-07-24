$(function(){
    $('textarea[name=description]').sceditorBBCodePlugin({
            toolbar: "bold,italic,underline,strike|left,center,right,justify|" +
            "bulletlist,orderedlist|link,unlink,image,youtube|source",
            resizeEnabled: "true",
            emoticonsEnabled: "false",
            emoticonsCompat: "false",
            enablePasteFiltering: "true"});

    //sceditorBBCodePlugin for validation, updates iframe on submit 
    $("button[name=submit]").click(function(){
        $("textarea[name=description]").data("sceditor").updateTextareaValue();
    });

	// paste plain text in sceditor
	$(".sceditor-container iframe").contents().find("body").bind('paste', function(e) {
		e.preventDefault();
		var text = (e.originalEvent || e).clipboardData.getData('text/plain');
		$(".sceditor-container iframe")[0].contentWindow.document.execCommand('insertText', false, text);
	});	
});