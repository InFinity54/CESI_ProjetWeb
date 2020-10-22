$(document).ready(function() {


    $("#profilepassword-form").validate({
        rules: {
            oldpwd: {
                required: true
            },
            newpwd: {
                required: true,
                equalTo: "#newpwdconfirm"
            },
            newpwdconfirm: {
                required: true,
                equalTo: "#newpwd"
            }
        },
        messages: {
            oldpwd: {
                required: "Ce champ est obligatoire."
            },
            newpwd: {
                required: "Ce champ est obligatoire.",
                equalTo: "Les mots de passe ne correspondent pas."
            },
            newpwdconfirm: {
                required: "Ce champ est obligatoire.",
                equalTo: "Les mots de passe ne correspondent pas."
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