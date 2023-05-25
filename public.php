<?php
require_once __DIR__ . '/recaptcha/autoload.php';
include_once ('db.php');
/**
 * Class plugins_recaptcha_public
 * Fichier pour l'éxecution frontend d'un plugin
 */
class plugins_recaptcha_public extends plugins_recaptcha_db {
	/**
	 * @var plugins_recaptcha_public $instance
	 */
	static protected plugins_recaptcha_public $instance;

    protected
        $data,$template,
        $modelDomain,
        $setBuildUrl;

    public
        $gRecaptchaResponse,
        $recaptcha_action,
        $conf,
        $active;

	/**
	 * @param frontend_model_template|null $t
	 */
    public function __construct(frontend_model_template $t = null) {
		if (isset(self::$instance) && self::$instance !== null) {
			foreach (get_object_vars(self::$instance) as $prop=>$value) {
				$this->{$prop} = $value;
			}
		}
		else {
			$this->data = new frontend_model_data($this);
			$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
			$this->modelDomain = new frontend_model_domain($this->template);
			$this->setBuildUrl = new http_url();
			$this->conf = $this->getItems('root',NULL,'one',false);
			$this->active = (bool)$this->conf['published'];
			self::$instance = $this;
		}

		// recaptcha v2
		if (http_request::isPost('g-recaptcha-response')) $this->gRecaptchaResponse = form_inputEscape::simpleClean($_POST['g-recaptcha-response']);
		// recaptcha v3
		elseif (http_request::isPost('recaptcha_response')) $this->gRecaptchaResponse = form_inputEscape::simpleClean($_POST['recaptcha_response']);
		if (http_request::isPost('recaptcha_action')) $this->recaptcha_action = form_inputEscape::simpleClean($_POST['recaptcha_action']);
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param array|int|null $id
     * @param string|null $context
     * @param bool|string $assign
     * @return array|bool
     */
    private function getItems(string $type, $id = null, string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * @return array
     */
    public function setItemData(): array {
        $setData = $this->getItems('root',NULL,'one',false);
        return $setData ?? [];
    }

    /**
     * @return bool
     */
    public function getRecaptcha(): bool {
        $domains = $this->modelDomain->getValidDomains();
        $data = $this->setItemData();
        if(!empty($data)) {
            foreach ($domains as $key) {
                if (isset($key['url_domain']) && $_SERVER['HTTP_HOST'] === $key['url_domain']) {
					if (isset($this->gRecaptchaResponse)) {
						// If the form submission includes the "g-captcha-response" field
						// Create an instance of the service using your secret
						$recaptcha = new \ReCaptcha\ReCaptcha($data['secret']);
						$resp = null;

						switch($data['version']) {
							case '2':
								if (isset($this->gRecaptchaResponse)) {
									// If the form submission includes the "g-captcha-response" field
									// Create an instance of the service using your secret
									$resp = $recaptcha->setExpectedHostname($key['url_domain'])
										->verify($this->gRecaptchaResponse, $_SERVER['REMOTE_ADDR']);
								}
								break;
							case '3':
								if (isset($this->gRecaptchaResponse)) {
									$resp = $recaptcha->setExpectedHostname($key['url_domain'])
										->setExpectedAction($this->recaptcha_action)
										->setScoreThreshold(0.5)
										->verify($this->gRecaptchaResponse, $_SERVER['REMOTE_ADDR']);
								}
								break;
						}

						if ($resp !== null && $resp->isSuccess()) {
							return true;
						} else {
							return false;
							//$errors = $resp->getErrorCodes();
						}
					}
                }
            }
        }
        return false;
    }
}