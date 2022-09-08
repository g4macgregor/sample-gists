(function ($) {
    $(document).ready(function () {
        /* Wizard */
        var current_slide = 1;
        var pagination_container = $('<div class="wiz-page-container"></div>');

        function wizard_init() {
            $("#product_wizard").append(pagination_container);
            for (var i = 1; i <= $(".wizard_question").length; i++) {
                var counter = i;
                var page = $('<button class="wiz-page" data-wizard-step="' + counter + '">' + counter + '</button>');
                pagination_container.append(page);
                if (counter === 1)
                    page.addClass('wiz-page-active');
                switch_page(page);
            }
        }

        function switch_page(page) {
            ///console.log(JSON.stringify(page));
            page.on('click', function () {
                open_close($(this).data('wizard-step'));
            });
        }

        function open_close(open) {
            $('.wizard_question').hide(50);
            $('.wizard_question[data-wizard-step=' + open + ']').show(100);
            $('.wiz-page.wiz-page-active').removeClass('wiz-page-active');
            $('.wiz-page[data-wizard-step=' + open + ']').addClass('wiz-page-active');
            current_slide = open;
        }

        function wizard() {
            wizard_init();

            $(".wizard_question").each(function (index, value) {
                if ($(this).data('wizard-step') != current_slide)
                    $(this).hide();
            });

            $(".wizard_question").find('input').on('change', function () {
                var tmp = $(this);
                var parent = $(this).parents('.wizard_question');
                current_slide = parent.data('wizard-step');

                if (current_slide == 4){
                    var str4 = $(this).val();
                    if (str4 == 'no'){
                        open_close(6);
                    } else {
                        open_close(parent.next().data('wizard-step'));
                    }

                } else {
                    open_close(parent.next().data('wizard-step'));
                }
            });

            $('[data-reveal]').on('closed.zf.reveal', function () {
                var modal = $(this);
                $(".wizard_question").each(function (index, value) {
                    var $checked = $(this).find('input[type=radio]:checked');
                    $checked.prop('checked', false);
                });
            });

            $(".final_question").on('click', function (e) {
                var addon = 'B';
                $(".wizard_question").each(function (index, value) {
                    var $checked = $(this).find('input[type=radio]:checked');
                    if ($checked.data('key'))
                        addon += $checked.data('key');
                });

                // get the value of the name attribute
                var year = $("input[name=year]:checked").val();
                if (!$("input[name=year]:checked").val()) {
                    open_close(1);
                    if ($('.wiz-error').length === 0) {
                        $('.wizard_question[data-wizard-step=' + 1 + ']').find('.question').append('<div class="wiz-error small-text-info">Please select a year for your software</div>');
                    }
                } else {
                    wizard_spin();
                    $('.final_question').remove();
                    window.location = '/product/' + year + '-w2-1099-forms-filer-software/' + '?wizard-addon=' + addon;
                }
            });

            var url = new URL(window.location.href);
        }

        function wizard_spin() {
            $(".wizard_question[data-wizard-step=9]").prepend('<div class="wiz-confirmation">We are taking you to your custom software build</div><div class="wiz-spinner"><img src="/wp-content/plugins/plugin-name/assets/img/Spinner.svg" alt="wizard-spinner" /></div>');
        }

        wizard();
        /* end wizard */
    })
})(jQuery);
