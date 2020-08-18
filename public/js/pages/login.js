$(document).ready(function() {
    $("#login-form").validate({
        rules: {
            username: {
                required: true
            },
            password: {
                required: true,
                password: true
            }
        },
        messages: {
            username: {
                required: "Veuillez saisir votre identifiant"
            },
            password: {
                required: "Veuillez saisir votre mot de passe"
            }
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