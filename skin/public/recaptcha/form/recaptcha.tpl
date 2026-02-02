{recaptcha_data}
{if is_array($recaptcha) && !empty($recaptcha)}
    {if $recaptcha.version eq '2'}
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&hl={$lang}" async defer></script>
        <div class="g-recaptcha" data-sitekey="{$recaptcha.apikey}"></div>
        <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" {*id="hiddenRecaptcha"*}>
    {elseif $recaptcha.version eq '3'}
        <script async src="https://www.google.com/recaptcha/api.js?render={$recaptcha.apikey}"></script>
        <script type="application/javascript">
            if(typeof grecaptcha === 'undefined') {
                var grecaptcha = {};
            }
            grecaptcha.ready = function(cb){
                if(typeof grecaptcha.execute === 'undefined') {
                    const c = '___grecaptcha_cfg';
                    window[c] = window[c] || {};
                    (window[c]['fns'] = window[c]['fns']||[]).push(cb);
                } else {
                    cb();
                }
            }
            function getRecaptcha() {ldelim}
                grecaptcha.ready(function () {
                    var actionInput = document.querySelector('.recaptcha_{$action} [name="recaptcha_action"]');
                    var responseInput = document.querySelector('.recaptcha_{$action} [name="recaptcha_response"]');

                    if (actionInput && responseInput) {
                        var grecaptcha_opts = actionInput.value;
                        grecaptcha.execute('{$recaptcha.apikey}', { action: grecaptcha_opts }).then(function (token) {
                            responseInput.value = token;
                        });
                    }
                });
                {rdelim}
            getRecaptcha();
            setInterval(function(){
                getRecaptcha();
            }, 90000);
        </script>
        <div class="recaptcha_{$action}">
            <input type="hidden" name="recaptcha_response" class="hiddenRecaptcha">
            <input type="hidden" name="recaptcha_action" value="{$action}">
        </div>
    {/if}
{/if}