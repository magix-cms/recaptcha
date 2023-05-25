{if is_array($data) && !empty($data)}
    {if $data.version eq '2'}
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&hl={$lang}" async defer></script>
        <div class="g-recaptcha" data-sitekey="{$data.apikey}"></div>
        <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" {*id="hiddenRecaptcha"*}>
    {elseif $data.version eq '3'}
        <script src="https://www.google.com/recaptcha/api.js?render={$data.apikey}"></script>
        <script>
            grecaptcha.ready(function () {
                var grecaptcha_opts = document.querySelector('.recaptcha_{$action} [name="recaptcha_action"]').value;
                grecaptcha.execute('{$data.apikey}', { action: grecaptcha_opts }).then(function (token) {
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