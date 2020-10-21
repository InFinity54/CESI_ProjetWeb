$(document).ready(function() {
    $('.dropify').dropify({
        messages: {
            'default': 'Déposez une photo ou cliquez ici pour en sélectionner une',
            'replace': 'Déposez une photo ou cliquez ici pour remplacer la photo',
            'error':   'Une erreur est survenue. Veuillz réessayer plus tard.'
        }
    });

    var telNumber = intlTelInput(document.querySelector("#tel"), {
        preferredCountries: ["fr"],
        hiddenInput: "telFull",
        utilsScript: "/vendors/js/utils.js"
    });

    var faxNumber = intlTelInput(document.querySelector("#fax"), {
        preferredCountries: ["fr"],
        hiddenInput: "faxFull",
        utilsScript: "/vendors/js/utils.js"
    });

    $("#agence-form").validate({
        rules: {
            photo: {
                required: false
            },
            nom: {
                required: true
            },
            adresse: {
                required: true
            },
            adressecomp: {
                required: false
            },
            codepostal: {
                required: true
            },
            ville: {
                required: true
            },
            tel: {
                required: true,
                isValidPhoneNumber: true
            },
            fax: {
                required: true,
                isValidPhoneNumber: true
            }
        },
        messages: {
            nom: {
                required: "Ce champ est obligatoire."
            },
            adresse: {
                required: "Ce champ est obligatoire."
            },
            codepostal: {
                required: "Ce champ est obligatoire."
            },
            ville: {
                required: "Ce champ est obligatoire."
            },
            tel: {
                required: "Ce champ est obligatoire.",
                isValidPhoneNumber: "Veuillez entrer un numéro de téléphone valide."
            },
            fax: {
                required: "Ce champ est obligatoire.",
                isValidPhoneNumber: "Veuillez entrer un numéro de fax valide."
            }
        },
        errorPlacement: function(error, element) {
            switch (element.attr("name"))
            {
                case "tel":
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