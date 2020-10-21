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
}, "Veuillez entrer un numéro de téléphone valide avec son indicatif.");