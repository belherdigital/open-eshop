$('#offer_valid').datepicker();

// VALIDATION with chosen fix
    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Please check your input."
    );

    var $form = $(".product_form");
    $form.validate({
        errorLabelContainer: $(".product_form div.error"),
        wrapper: 'div',
        rules: {
            title: {minlength:2},
            price: {regex:"^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"}
        },
        messages: {
            price:{regex: "Format is incorect"}
        }
    });
    
    //chosen fix
    var settings = $.data($form[0], 'validator').settings;
    settings.ignore += ':not(.chzn-done)'; // product_form location(any chosen) texarea
    settings.ignore += ':not(.sceditor-container)'; // product_form description texarea
    // end VALIDATION

    //datepicker in case date field exists
    if($('.cf_date_fields').length != 0){
        $('.cf_date_fields').datepicker();}