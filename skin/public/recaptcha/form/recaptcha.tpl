{recaptcha_data}
{if is_array($recaptcha) && !empty($recaptcha)}
    {if $recaptcha.version eq '2'}
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&hl={$lang}" async defer></script>
        <div class="g-recaptcha" data-sitekey="{$recaptcha.apikey}"></div>
        <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" {*id="hiddenRecaptcha"*}>
    {elseif $recaptcha.version eq '3'}
        <script src="https://www.google.com/recaptcha/api.js?render={$recaptcha.apikey}"></script>
        <script>
            grecaptcha.ready(function () {
                var grecaptcha_opts = document.querySelector('.recaptcha_{$action} [name="recaptcha_action"]').value;
                grecaptcha.execute('{$recaptcha.apikey}', { action: grecaptcha_opts }).then(function (token) {
                    var recaptchaResponse = document.querySelector('.recaptcha_{$action} [name="recaptcha_response"]');
                    recaptchaResponse.value = token;
                });
            });
        </script>
        <div class="recaptcha_{$action}">
            <input type="hidden" name="recaptcha_response" class="hiddenRecaptcha">
            <input type="hidden" name="recaptcha_action" value="{$action}">
        </div>
    {/if}
{/if}