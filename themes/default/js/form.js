//sceditorBBCodePlugin for validation, updates iframe on submit 
    $("button[name=submit]").click(function(){
        $("textarea[name=description]").data("sceditor").updateOriginal();
    });
    // VALIDATION with chosen fix
$.validator.addMethod(
    "regex",
    function(value, element, regexp) {
        var re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    },
    "Please check your input."
);

var $params = {rules:{}, messages:{}};
$params['rules']['price'] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
$params['messages']['price'] = "Format is incorect";

var $form = $(".product_form");
$form.validate($params);

// //chosen fix
var settings = $.data($form[0], 'validator').settings;
settings.ignore += ':not(.chzn-done)'; // edit_ad_form location(any chosen) texarea
settings.ignore += ':not(#description)'; // edit_ad_form description texarea
// end VALIDATION

//datepicker in case date field exists
if($('.cf_date_fields').length != 0){
    $('.cf_date_fields').datepicker();}

