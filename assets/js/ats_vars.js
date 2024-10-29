jQuery(document).ready(function () {
    jQuery(document).on('change', '#all_same', function() {
        var firstselect = jQuery('table.variations select:first');
        var firsttr = jQuery('table.variations tr:first');
        var selectval = jQuery(firstselect).val();

        var alltrs = jQuery(firstselect).parent().parent().nextAll('tr');
        var allselects = jQuery(firstselect).parent().parent().nextAll('tr').find('select');

        if (selectval == '' || jQuery(this).prop('checked') == false) {
            jQuery(this).prop('checked', false);
            alltrs.show();
            if (selectval == '') {
                jQuery('#ats_no_option').show();
            }
        } else {
            jQuery('#ats_no_option').hide();
            alltrs.hide();
            allselects.each(function (index) {
                jQuery(this).val(selectval);
            });
            jQuery('form.variations_form.cart').trigger("check_variations");
            jQuery('form.variations_form.cart').trigger("woocommerce_variation_select_change");
            jQuery('form.variations_form.cart').trigger("check_variations");
            jQuery('form.variations_form.cart').trigger('woocommerce_variation_has_changed');
        }
    });

    jQuery('a.reset_variations').click(function () {
        jQuery('#all_same').prop('checked', false);
    });

    var topoption = jQuery('table.variations tr:first td.value select');

    topoption.change(function () {
        jQuery('#ats_no_option').hide();
        if (jQuery('#all_same').is(':checked')) {
            if (topoption.val() == '') {
                var alltrs = jQuery(this).parent().parent().nextAll('tr');
                alltrs.show();
                jQuery('#all_same').prop('checked', false);
            } else {
                var allselects = jQuery(this).parent().parent().nextAll('tr').find('select');
                allselects.each(function (index) {
                    jQuery(this).val(topoption.val());
                });
                jQuery('form.variations_form.cart').trigger("check_variations");
                jQuery('form.variations_form.cart').trigger("woocommerce_variation_select_change");
                jQuery('form.variations_form.cart').trigger("check_variations");
                jQuery('form.variations_form.cart').trigger('woocommerce_variation_has_changed');
            }
        }
    });

});