(function ($) {
    $(document).ready(function () {

        $('#customize-theme-controls').on('change', '#customize-control-et_divi-header_style select', function () {
            let $input = $(this);

            setupVerticalNav($input);
        });

        setupVerticalNav($('#customize-control-et_divi-header_style select'));

    });

    function setupVerticalNav($input) {
        let $tn_vertical_nav_option = $('#customize-control-et_divi-vertical_nav'),
            $tn_vertical_nav_input = $tn_vertical_nav_option.find('input[type=checkbox]');

        if ('stacked' === $input.val()) {
            $tn_vertical_nav_option.hide();
            $tn_vertical_nav_input.attr('checked', false);
            $tn_vertical_nav_input.change();

        } else {
            $tn_vertical_nav_option.show();
        }
    }

})(jQuery);