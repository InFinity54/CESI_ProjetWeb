$(document).ready(function() {
    $('.dropify').dropify({
        messages: {
            'default': 'Déposez une photo ou cliquez ici pour en sélectionner une',
            'replace': 'Déposez une photo ou cliquez ici pour remplacer la photo',
            'error':   'Une erreur est survenue. Veuillz réessayer plus tard.'
        }
    });

    $.validator.addMethod("validnumberplate", function(value, element) {
        return this.optional(element) || /^([A-Z]{2})-([0-9]{3})-([A-Z]{2})\s]+$/i.test(value);
    }, "Veuillez saisir un numéro d'immatriculation valide.");

    $("#vehicle-form").validate({
        rules: {
            photo: {
                required: false
            },
            marque: {
                required: true
            },
            modele: {
                required: true
            },
            immat: {
                required: true,
                validnumberplate: true
            },
            datefabrication: {
                required: true
            },
            agence: {
                required: true
            },
            statut: {
                required: true
            },
            hauteur: {
                required: true
            },
            largeur: {
                required: true
            },
            poids: {
                required: true
            },
            puissance: {
                required: true
            }
        },
        messages: {
            marque: {
                required: "Ce champ est obligatoire."
            },
            modele: {
                required: "Ce champ est obligatoire."
            },
            immat: {
                required: "Ce champ est obligatoire.",
                validnumberplate: "Veuillez saisir un numéro d'immatriculation valide."
            },
            datefabrication: {
                required: "Ce champ est obligatoire."
            },
            agence: {
                required: "Ce champ est obligatoire."
            },
            statut: {
                required: "Ce champ est obligatoire."
            },
            hauteur: {
                required: "Ce champ est obligatoire."
            },
            largeur: {
                required: "Ce champ est obligatoire."
            },
            poids: {
                required: "Ce champ est obligatoire."
            },
            puissance: {
                required: "Ce champ est obligatoire."
            }
        },
        errorPlacement: function(error, element) {
            switch (element.attr("name"))
            {
                case "hauteur":
                case "largeur":
                case "poids":
                case "puissance":
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