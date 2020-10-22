$(document).ready(function() {
    $('.dropify').dropify({
        messages: {
            'default': 'Déposez une photo ou cliquez ici pour en sélectionner une',
            'replace': 'Déposez une photo ou cliquez ici pour remplacer la photo',
            'error':   'Une erreur est survenue. Veuillz réessayer plus tard.'
        }
    });

    var fixeNumber = intlTelInput(document.querySelector("#fixe"), {
        preferredCountries: ["fr"],
        hiddenInput: "fixeFull",
        utilsScript: "/vendors/js/utils.js"
    });

    var mobileNumber = intlTelInput(document.querySelector("#mobile"), {
        preferredCountries: ["fr"],
        hiddenInput: "mobileFull",
        utilsScript: "/vendors/js/utils.js"
    });

    var faxNumber = intlTelInput(document.querySelector("#fax"), {
        preferredCountries: ["fr"],
        hiddenInput: "faxFull",
        utilsScript: "/vendors/js/utils.js"
    });

    $("#profile-form").validate({
        rules: {
            photo: {
                required: false
            },
            nom: {
                required: true
            },
            prenom: {
                required: true
            },
            mobile: {
                required: true,
                isValidPhoneNumber: true
            },
            fixe: {
                required: false,
                isValidPhoneNumber: true
            },
            fax: {
                required: false,
                isValidPhoneNumber: true
            },
            email: {
                required: true,
                validmail: true
            }
        },
        messages: {
            nom: {
                required: "Ce champ est obligatoire."
            },
            prenom: {
                required: "Ce champ est obligatoire."
            },
            mobile: {
                required: "Ce champ est obligatoire.",
                isValidPhoneNumber: "Veuillez saisir un numéro de téléphone valide."
            },
            fixe: {
                isValidPhoneNumber: "Veuillez saisir un numéro de téléphone valide."
            },
            fax: {
                isValidPhoneNumber: "Veuillez saisir un numéro de fax valide."
            },
            email: {
                required: "Ce champ est obligatoire.",
                validmail: "Veuillez saisir une adresse e-mail valide."
            }
        },
        errorPlacement: function(error, element) {
            switch (element.attr("name"))
            {
                case "fixe":
                case "mobile":
                case "fax":
                    error.appendTo(element.parent("div").parent("div"));
                    break;
                default:
                    error.insertAfter(element);
                    break;
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