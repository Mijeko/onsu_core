var rmzGoogle = rmzGoogle ? rmzGoogle : {};
(function($) {
    "use strict";
    rmzGoogle.initGoogleCaptcha = function(SITE_KEY,AJAX_PATH){
        if (typeof grecaptcha != 'undefined' && typeof grecaptcha.render != 'undefined'){
            var $captches = $('.rmz_google_captcha_for_load');
            rmzGoogle.loadedCaptches = rmzGoogle.loadedCaptches ? rmzGoogle.loadedCaptches : {};
            $.each($captches, function (index, value) {
                var $this = $(this),
                    google_id = $this.attr('id'),
                    bCaptchaInit = $this.children().length > 0;

                $this.closest('form').find('[type="submit"]').attr('disabled',true);
                $this.closest('form').data('selector-captcha','google_captcha_' + google_id);

                if (typeof  rmzGoogle.loadedCaptches[google_id] == 'undefined' || !bCaptchaInit) {
                    rmzGoogle.loadedCaptches[google_id] = grecaptcha.render(
                        document.getElementById(google_id), {
                            'sitekey': SITE_KEY,
                            'callback': function (response) {
                                var data = {'g-recaptcha-response': response};
                                $.ajax({
                                    url: AJAX_PATH,
                                    data: data,
                                    type: 'POST',
                                    success: function (answ) {
                                        var inputSaccses = document.createElement('input'),
                                            widget;
                                        inputSaccses.setAttribute('type', 'hidden');
                                        inputSaccses.setAttribute('name', 'yenisite_google_captcha_success');

                                        if (answ == 'error') {
                                            inputSaccses.setAttribute('value', 'N');
                                        } else {
                                            inputSaccses.setAttribute('value', 'Y');
                                            $this.siblings('.checkbox_for_captcha_google').attr('checked',true).change();
                                        }

                                        if (answ == 'error') {
                                            $('#' + google_id).closest('form').find('[type="submit"]').attr('disabled', true);
                                        } else {
                                            $('#' + google_id).closest('form').find('[type="submit"]').attr('disabled', false);
                                        }
                                        widget = document.getElementById(google_id);
                                        answ == 'error' ? grecaptcha.reset(rmzGoogle.loadedCaptches[google_id]) : '';

                                        widget.appendChild(inputSaccses);
                                    }
                                });
                            },
                        });
                } else{
                    grecaptcha.reset(rmzGoogle.loadedCaptches[google_id]);
                }
            })
        } else{
            setTimeout(rmzGoogle.initGoogleCaptcha.bind(this,SITE_KEY,AJAX_PATH),500);
        }
    };
})(jQuery);