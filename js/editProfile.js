$(document).ready(function () {

    $('#contact-form').validate({
        rules: {
            phone_number: {
                minlength: 10,
				maxlength: 10,
                required: true
            },
            email: {
                required: true,
                email: true
            }
        },
        highlight: function (element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function (element) {
            element.text('OK!').addClass('valid')
                .closest('.control-group').removeClass('error').addClass('success');
        }
    });

});