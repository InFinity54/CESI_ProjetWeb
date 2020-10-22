$(document).ready(function() {
    $("#status-form").validate({
        rules: {
            nom: {
                required: true
            },
            couleur: {
                required: true
            }
        },
        messages: {
            nom: {
                required: "Ce champ est obligatoire."
            },
            couleur: {
                required: "Ce champ est obligatoire."
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        highlight: function(element, errorClass) {
            $(element).addClass("error is-invalid");
            $(element).removeClass("success");
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass("error is-invalid");
            $(element).addClass("success");
        }
    });
});