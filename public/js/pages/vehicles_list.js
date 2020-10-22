$(document).ready(function() {
    $("#brand").on("change", function() {
        var value = this.value;

        $("#model > option").each(function() {
            if ($(this).text !== "Modèle") {
                if ($(this).data("brand") === value) {
                    $(this).removeAttr("hidden");
                } else {
                    $(this).prop("hidden", "hidden");
                }
            }
        });
    });

    $("#morefilterstoggle").on("click", function() {
        var morefilters = $("#morefilters");
        if (document.getElementById("morefilters").classList.contains("d-none")) {
            document.getElementById("morefilters").classList.remove("d-none");
            morefilters.hide();
        }
        morefilters.slideToggle();
    });

    $("#weight").slider({});
    $("#power").slider({});
});