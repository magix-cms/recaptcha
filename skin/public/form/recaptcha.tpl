{if is_array($data) && !empty($data)}
    <div class="g-recaptcha" data-sitekey="{$data.apikey}"></div>
    <script type="text/javascript"
            src="https://www.google.com/recaptcha/api.js?hl={$lang}">
    </script>
    <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
{/if}