{if is_array($data) && !empty($data)}
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&hl={$lang}" async defer></script>
    <div class="g-recaptcha" data-sitekey="{$data.apikey}"></div>
    <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" {*id="hiddenRecaptcha"*}>
{/if}