<?php
function smarty_function_recaptcha_data($params, $smarty){
    $modelTemplate = $smarty->tpl_vars['modelTemplate']->value instanceof frontend_model_template ? $smarty->tpl_vars['modelTemplate']->value : new frontend_model_template();
    $collection = new frontend_model_plugins();
    if($collection->isInstalled('recaptcha')) {
        $recaptcha = new plugins_recaptcha_public($modelTemplate);
        if($recaptcha->active) $smarty->assign('recaptcha',$recaptcha->conf);
    }
}