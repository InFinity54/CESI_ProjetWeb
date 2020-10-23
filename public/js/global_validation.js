$.validator.addMethod("isValidPhoneNumber", function(value, element) {
    if (document.activeElement !== element && value !== "")
    {
        var iti = window.intlTelInputGlobals.getInstance(element);
        return iti.isValidNumber();
    }
    else
    {
        return true;
    }
}, "Veuillez entrer un numéro de téléphone valide. L'indicatif international est obligatoire.");

$.validator.addMethod("cityname", function(value, element) {
    return this.optional(element) || /^[a-zA-Zàâäéèêëïîôöùûüÿç\-\s]+$/i.test(value);
}, "Veuillez saisir un nom de ville valide.");

$.validator.addMethod("validmail", function(value, element) {
    return this.optional(element) || /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$/s.test(value);
}, "Veuillez saisir une adresse e-mail valide.");