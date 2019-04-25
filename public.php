<?php
require_once __DIR__ . '/recaptcha/autoload.php';
include_once ('db.php');
/**
 * Class plugins_recaptcha_public
 * Fichier pour l'éxecution frontend d'un plugin
 */
class plugins_recaptcha_public extends plugins_recaptcha_db{

    protected $data,$template,$collectionDomain,$modelDomain,$setBuildUrl;
    public $gRecaptchaResponse,$recaptcha_action;

    public function __construct($t = null)
    {
        $this->data = new frontend_model_data($this);
        $this->template = $t ? $t : new frontend_model_template();
        $formClean = new form_inputEscape();
        $this->modelDomain = new frontend_model_domain($this->template);
        $this->setBuildUrl = new http_url();
        // recaptcha v2
        if (http_request::isPost('g-recaptcha-response')) $this->gRecaptchaResponse = $formClean->simpleClean($_POST['g-recaptcha-response']);
        // recaptcha v3
        elseif (http_request::isPost('recaptcha_response')) $this->gRecaptchaResponse = $formClean->simpleClean($_POST['recaptcha_response']);
        if (http_request::isPost('recaptcha_action')) $this->recaptcha_action = $formClean->simpleClean($_POST['recaptcha_action']);
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }


    /**
     * @return mixed
     */
    public function setItemData(){
        $setData = $this->getItems('root',NULL,'one',false);
        return $setData;
    }

    /**
     * @return bool
     */
    public function getRecaptcha(){
        $domains = $this->modelDomain->getValidDomains();
        $data = $this->setItemData();
        if($data != null) {
            foreach ($domains as $key) {
                if (isset($key['url_domain']) && $_SERVER['HTTP_HOST'] === $key['url_domain']) {
                    switch($data['version']) {
                        case '2':
                            if (isset($this->gRecaptchaResponse)) {
                                // If the form submission includes the "g-captcha-response" field
                                // Create an instance of the service using your secret
                                $recaptcha = new \ReCaptcha\ReCaptcha($data['secret']);
                                $resp = $recaptcha->setExpectedHostname($key['url_domain'])
                                    ->verify($this->gRecaptchaResponse, $_SERVER['REMOTE_ADDR']);
                                if ($resp->isSuccess()) {
                                    return true;
                                } else {
                                    return false;
                                    //$errors = $resp->getErrorCodes();
                                }
                            }
                        break;
                        case '3':
                            if (isset($this->gRecaptchaResponse)) {
                                $recaptcha = new \ReCaptcha\ReCaptcha($data['secret']);
                                $resp = $recaptcha->setExpectedHostname($key['url_domain'])
                                    ->setExpectedAction($this->recaptcha_action)
                                    ->setScoreThreshold(0.5)
                                    ->verify($this->gRecaptchaResponse, $_SERVER['REMOTE_ADDR']);
                                if ($resp->isSuccess()) {
                                    return true;
                                    /*header('Content-type:application/json');
                                    echo json_encode($resp->toArray());*/
                                } else {
                                    return false;
                                }
                            }
                        break;
                    }
                }
            }
        }
    }
}
?>