
if($('#offer_valid').length != 0){
        $('#offer_valid').datepicker();}
if($('#featured').length != 0){
        $('#featured').datepicker();}

$('.fileinput').fileinput();

$(function () {

    var jqXHR = null;
    var json = null;
    var file_name = null;
    $('#fileupload').fileupload({
        maxChunkSize:9000000,
        // uploadedBytes:9000000,
        recalculateProgress:true,
        maxNumberOfFiles:1,
        maxFileSize:$('#fileupload').attr('data-size'),
        type: "POST",
        data: json,
        // dataType: "json",
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        },
        add: function (e, data) {

            data.context = $('<p/>').text(data.files[0]['name']).appendTo('#files');
            data.context = $('<button/>').addClass('btn btn-primary')
                .text('Upload')
                .appendTo('#files')
                .click(function () {
                    data.context = $('<p/>').text('Uploading...').replaceAll($(this)); 
                    jqXHR = data.submit();
                });
                
            data.context = $('<button/>').addClass('btn btn-warning')
                .text('Remove')
                .addClass('cancel')
                .appendTo('#files')
                .click(function () {
                    $('#files').text(''); 
                });
        },
        done: function (e, data) {
            file_name = data.result;
            $('#uploadedfile').val(file_name);
             
            $('#fileupload').fileupload('disable');
            
            data.context = $('#files').text('Upload finished.');
            $('#delete-button-file').removeClass('hide');
        },
        error: function (e, data) {
            data.context = $('#files').text('There was some error while uploading!');
        },
        fail: function (e, data) {
            data.context = $('#files').text('Failed to upload!');
        },
    
    });
$('button.cancel').click(function(){
        alert(123);
    });
    $('#delete-button-file').click(function(){
        $.ajax({
            type: "POST",
            url: "delete_file",
            data: { 'file_name': file_name },
            success: function(e, data)
            {
                $('#files').text('Deleted.');
                $('#fileupload').fileupload('enable');
                $('#delete-button-file').addClass('hide');
            }
        });
    }); 
});


