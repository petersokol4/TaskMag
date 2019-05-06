// Forms Validation

$(document).ready(function () {                 //ak je dokument pripravený

    $.validator.setDefaults( {

        errorElement: "em",
        errorClass: "invalid-feedback d-block",
        highlight: function(element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function(element) {
            $(element).removeClass("is-invalid");
        },
        errorPlacement: function (error, element) {
            if (element.is(":checkbox") || element.is(":radio") || element.is(":input")  ) {
                error.appendTo(element.parents('.putError'));
            } else {
                error.insertAfter(element);
            }
        }
    } );

    $.validator.addMethod("strongPassword",
        function(value, element, param) {
            if (this.optional(element)) {
                return true;
            } else if (!/[A-Z]/.test(value)) {
                return false;
            } else if (!/[a-z]/.test(value)) {
                return false;
            } else if (!/[0-9]/.test(value)) {
                return false;
            }

            return true;
        },
        "Heslo musí obsahovať minimálne jedno veľké a jedno malé písmeno a minimálne jednu číslicu.");

    $.validator.addMethod( "lettersonly", function( value, element ) {
        return this.optional( element ) || /^[a-ž]+$/i.test( value );
    });

    $.validator.addMethod( "nowhitespace", function( value, element ) {
        return this.optional( element ) || /^\S+$/i.test( value );
    });

});

// Toggle Show/Hide Password

$(document).on('click', '.toggle-password', function() {
    $(this).toggleClass("show-pass");
    var input = $("#upass");
    if (input.attr("type") === "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

// Toggle Show/Hide Password Control

$(document).on('click', '.toggle-password-c', function() {
    $(this).toggleClass("show-pass");
    var input = $("#cpass");
    if (input.attr("type") === "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});
