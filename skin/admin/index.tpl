{extends file="layout.tpl"}
{block name='head:title'}recaptcha{/block}
{block name='body:id'}recaptcha{/block}
{block name='article:header'}
    <h1 class="h2">Google Recaptcha</h1>
{/block}
{block name='article:content'}
    {if {employee_access type="view" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-ph-12">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header">
                    <h2 class="panel-heading h5">Recaptcha Configuration</h2>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>

                    <div class="row">
                        <form id="bridge_config" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-xs-12 col-md-6">
                            <div class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <div class="form-group">
                                        <label for="apiKey">ApiKey :</label>
                                        <input type="text" class="form-control" id="recaptchaData[apiKey]" name="recaptchaData[apiKey]" value="{$page.apikey}" size="50" />
                                    </div>
                                </div>
                                <div class="col-ph-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="recaptchaData[published]">Statut</label>
                                        <input id="recaptchaData[published]" data-toggle="toggle" type="checkbox" name="recaptchaData[published]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if $page.published} checked{/if}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <div class="form-group">
                                        <label for="recaptchaData[secret]">Secret :</label>
                                        <input type="text" class="form-control" id="recaptchaData[secret]" name="recaptchaData[secret]" value="{$page.secret}" size="50" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="recaptchaData[version]">Version* :</label>
                                        <select name="recaptchaData[version]" id="recaptchaData[version]" class="form-control required" required>
                                            <option value="">{#select_version#}</option>
                                            <option value="2"{if $page.version eq '2'} selected{/if}>v2</option>
                                            <option value="3"{if $page.version eq '3'} selected{/if}>v3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="submit">
                                <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}