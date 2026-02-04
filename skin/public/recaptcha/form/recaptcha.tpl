{recaptcha_data}
{if is_array($recaptcha) && !empty($recaptcha)}
    {if $recaptcha.version eq '2'}
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&hl={$lang}" async defer></script>
        <div class="g-recaptcha" data-sitekey="{$recaptcha.apikey}"></div>
        <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" {*id="hiddenRecaptcha"*}>
    {elseif $recaptcha.version eq '3'}
        <script async src="https://www.google.com/recaptcha/api.js?render={$recaptcha.apikey}"></script>
        <script type="application/javascript">
            function updateRecaptcha() {ldelim}
                if (typeof grecaptcha !== 'undefined' && grecaptcha.execute) {
                    grecaptcha.ready(function () {
                        grecaptcha.execute('{$recaptcha.apikey}', { action: '{$action}' }).then(function (token) {
                            var responseInput = document.querySelector('.recaptcha_{$action} [name="recaptcha_response"]');
                            if (responseInput) {
                                responseInput.value = token;
                            }
                        });
                    });
                }
                {rdelim}

            setTimeout(updateRecaptcha, 1000);
            setInterval(updateRecaptcha, 90000);

            document.addEventListener('click', function (e) {

                var submitBtn = e.target.closest('.recaptcha_{$action} ~ * [type="submit"]') || e.target.closest('form [type="submit"]');

                if (submitBtn) {
                    submitBtn.classList.add('is-submitting');
                    updateRecaptcha();

                    setTimeout(function() {
                        submitBtn.classList.remove('is-submitting');
                    }, 3000);
                }
            });
        </script>

        <div class="recaptcha_{$action}">
            <input type="hidden" name="recaptcha_response" value="">
            <input type="hidden" name="recaptcha_action" value="{$action}">
        </div>
    {/if}
{/if}