
if($('#offer_valid').length != 0){
        $('#offer_valid').datepicker();}
if($('#featured').length != 0){
        $('#featured').datepicker();}

$('.fileinput').fileinput();

// PREVENTS droping files in browser, if already something was uploaded 
window.addEventListener("dragover",function(e){
  e = e || event;
  e.preventDefault();
},false);
window.addEventListener("drop",function(e){
  e = e || event;
  e.preventDefault();
},false);
// end preventnts drop

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
            jqXHR = data.submit();
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

//validate form
$(function(){
    
    // VALIDATION with chosen fix
    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        }
    );
    
    var $params = {rules:{}, messages:{}};
    $params['rules']['price'] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
    $params['rules']['price_offer'] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
    $params['rules']['licenses'] = {number: true};
    $params['rules']['offer_valid'] = {date: true};
    $params['rules']['license_days'] = {number: true};
    $params['rules']['support_days'] = {number: true};
    $params['rules']['url_buy'] = {maxlength: 200};
    $params['rules']['url_demo'] = {maxlength: 200};
    $params['rules']['version'] = {maxlength: 200};
    $params['rules']['featured'] = {date: true};

    $(this).validate($params)
    
    var settings = $.data($form[0], 'validator').settings;

});