<?php

add_action( 'woocommerce_after_single_product', 'prefix_disable_hidden_fields', 9999 );

function prefix_disable_hidden_fields() {
?>
    <script>
        let url = new URL(window.location.href);
        var $addon = null;
        var $upgrade = null;
        var $upgrading = false;

        if (url.searchParams.get('wizard-addon')) {
            $addon = url.searchParams.get('wizard-addon');
        } else {
            if (url.searchParams.get('upgrade')) {
                // does it even have a product
                var pathArray = window.location.pathname.split('/');

                if (pathArray[1] != ("product")) {
                    window.location.href = "https://dev.example.com/"
                }

                $upgrade = url.searchParams.get('upgrade');

                // Code to check Serial that we were passed
                jQuery(document).ready(function ($) {
                    // This is the variable we are passing via AJAX
                    var upgrade = $upgrade;

                    // This does the ajax request
                    $.ajax({
                        url: ajaxurl,
                        data: {
                            'action': 'is_valid_serial_ajax_request',
                            'upgrade': upgrade
                        },
                        success: function (data) {
                            if (data == 0) {
                                alert("The product upgrade you selected is no longer available.");

                                $addon = null;
                                window.location.href = window.location.href.split('?')[0];
                            }
                        },
                        error: function (errorThrown) {
                            window.alert(errorThrown);
                        }
                    });
                });
                // break up the serial and feature codes
                var $serialCode = $upgrade.substr(0, 5);
                var $featureCodes = $upgrade.substr(6, ($upgrade.length - 5));
                $addon = $featureCodes;
                $upgrading = true;
            }
        }

        // Looking to see if addon is null
        if ($addon != null) {
            jQuery(document).ready(function ($) {

                function disableHiddenFields() {
                    $('form.cart').find('.pewc-item').each(function () {
                        switch ($(this).attr('data-field-label')) {
                            case 'Software Generated Forms':
                                if ($addon.includes('L')) {
                                    $(this).find('.pewc-form-field').prop('checked', true);

                                    if ($upgrading == true) {
                                        $(this).show().css({
                                            visibility: 'visible',
                                            opacity: 0.5
                                        }).find('.pewc-form-field').prop('disabled', true);
                                        $(this).find('.pewc-field-price').text('previously purchased');
                                        $(this).attr('data-price', 0);
                                    }
                                }
                                break;
                            case 'AMS Payroll':
                                if ($addon.includes('P')) {
                                    $(this).find('.pewc-form-field').prop('checked', true);

                                    if ($upgrading == true) {
                                        $(this).show().css({
                                            visibility: 'visible',
                                            opacity: 0.5
                                        }).find('.pewc-form-field').prop('disabled', true);
                                        $(this).find('.pewc-field-price').text('previously purchased');
                                        $(this).find('data-field-price').val(0);
                                        $(this).attr('data-price', 0);
                                    }
                                }
                                break;
                            case 'E-File Direct':
                                if ($addon.includes('M')) {
                                    $(this).find('.pewc-form-field').prop('checked', true);

                                    if ($upgrading == true) {
                                        $(this).show().css({
                                            visibility: 'visible',
                                            opacity: 0.5
                                        }).find('.pewc-form-field').prop('disabled', true);
                                        $(this).find('.pewc-field-price').text('previously purchased');
                                        $(this).attr('data-price', 0);
                                    }
                                }
                                break;
                            case 'Forms Filer Plus':
                                if ($addon.includes('F')) {
                                    $(this).find('.pewc-form-field').prop('checked', true);

                                    if ($upgrading == true) {
                                        $(this).show().css({
                                            visibility: 'visible',
                                            opacity: 0.5
                                        }).find('.pewc-form-field').prop('disabled', true);
                                        $(this).find('.pewc-field-price').text('previously purchased');
                                        $(this).attr('data-price', 0);
                                    }
                                }
                                break;
                            case 'Affordable Care Act Filer':
                                if ($addon.includes('A')) {
                                    $(this).find('.pewc-form-field').prop('checked', true);

                                    if ($upgrading == true) {
                                        $(this).show().css({
                                            visibility: 'visible',
                                            opacity: 0.5
                                        }).find('.pewc-form-field').prop('disabled', true);
                                        $(this).find('.pewc-field-price').text('previously purchased');
                                        $(this).attr('data-price', 0);
                                    }
                                }
                                break;
                            case '1042-S Filer':
                                if ($addon.includes('S')) {
                                    $(this).find('.pewc-form-field').prop('checked', true);

                                    if ($upgrading == true) {
                                        $(this).show().css({
                                            visibility: 'visible',
                                            opacity: 0.5
                                        }).find('.pewc-form-field').prop('disabled', true);
                                        $(this).find('.pewc-field-price').text('previously purchased');
                                        $(this).attr('data-price', 0);
                                    }
                                }
                                break;
                            case 'Upgrade':
                                // Set the serial code that we are upgrading
                                $(this).find('.pewc-form-field').val($serialCode);
                                break;
                            case 'Base W2/1099 Forms Filer included ($84.00)':
                                break;
                            case 'Base W2/1099 Forms Filer included ($39.50)':
                                break;
                            default:
                                alert('Not Found');
                        }
                    });
                }

                disableHiddenFields();

                var da = $.parseJSON($('.variations_form').attr('data-product_variations'));
                da[0].display_price = 0;
                da[0].display_regular_price = 0;
                da[1].display_price = 0;
                da[1].display_regular_price = 0;
                var update = JSON.stringify(da);
                $('.variations_form').attr('data-product_variations', update);

                if ($upgrading == true) {
                    $('#pewc-per-product-total').text('previously purchased');
                    $('button.single_add_to_cart_button').prop('disabled', true);
                }

                $('form.cart').find(':checkbox').each(function () {
                    $(this).find('.pewc-form-field').prop('checked', false);
                    $(this).change(function (event) {
                        $('button.single_add_to_cart_button').prop('disabled', false);
                    })
                });
            });
        } else { // $addon == null which means we have a regular order
            jQuery(document).ready(function ($) {
                function showBaseField() {
                    $('form.cart').find('.pewc-item').each(function () {
                        switch ($(this).attr('data-field-label')) {
                            case 'Software Generated Forms':
                                break;
                            case 'AMS Payroll':
                                break;
                            case 'E-File Direct':
                                break;
                            case 'Forms Filer Plus':
                                break;
                            case 'Affordable Care Act Filer':
                                break;
                            case '1042-S Filer':
                                break;
                            case 'Upgrade'://capitalized here
                                break;
                            case 'Base W2/1099 Forms Filer included ($84.00)':
                                $(this).find('.pewc-form-field').val(" ");
                                break;
                            case 'Base W2/1099 Forms Filer included ($39.50)':
                                $(this).find('.pewc-form-field').val(" ");
                                break;
                            default:
                        }
                    });
                }

                showBaseField();
            });
        }
    </script>
	<?php
}
