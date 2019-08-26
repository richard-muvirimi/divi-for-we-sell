(function ($) {
    $(document).ready(function () {

        //below function allows label to also initiate search when clicked if search input has text
        $("#tn-search-form-container .tn-search-label").click((event) => {
            if ($("#tn-search-form-container .et-search-field#s").val() !== "") {
                event.preventDefault();
                $('#searchsubmit_header').click();
            }
        });

    });

})(jQuery);