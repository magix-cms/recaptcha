<?php
require_once __DIR__ . '/recaptcha/autoload.php';
include_once ('db.php');
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2021 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
/**
 * @category plugin
 * @package recaptcha
 * @copyright MAGIX CMS Copyright (c) 2011 Gerits Aurelien, http://www.magix-dev.be, http://www.magix-cms.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @create 20-12-2021
 * @author Aurélien Gérits <aurelien@magix-cms.com>
 * @name plugins_recaptcha_public
 */
class plugins_recaptcha_public extends plugins_recaptcha_db {
	/**
	 * @var frontend_model_template $template
	 * @var frontend_model_data $data
	 * @var frontend_model_domain $modelDomain
	 */
    protected frontend_model_template $template;
    protected frontend_model_data $data;
    protected frontend_model_domain $modelDomain;

	/**
	 * @var bool $active
	 */
	public bool $active;

	/**
	 * @var string $response
	 * @var string $action
	 */
    public string
        $response,
        $action;

	/**
	 * @var array $conf
	 */
    public array $conf;

	/**
	 * @param frontend_model_template|null $t
	 */
    public function __construct(?frontend_model_template $t = null) {
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
		$this->data = new frontend_model_data($this);
        $this->modelDomain = new frontend_model_domain($this->template);
        $this->conf = $this->getItems('config',NULL,'one',false) ?: [];
        $this->active = (bool)$this->conf['published'];
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|array|null $id
     * @param string|null $context
     * @param bool|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = null, ?string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * @return bool
     */
    public function getRecaptcha(): bool {
        $domains = $this->modelDomain->getValidDomains();
        if(!empty($this->conf) && !empty($domains)) {
            foreach ($domains as $domain) {
                if (isset($domain['url_domain']) && $_SERVER['HTTP_HOST'] === $domain['url_domain']) {
					if (http_request::isMethod('POST')) {
						// If the form submission includes the "g-captcha-response" field
						// Create an instance of the service using your secret
						$recaptcha = new \ReCaptcha\ReCaptcha($this->conf['secret']);
						$resp = null;

						switch($this->conf['version']) {
							case '2':
								if (http_request::isPost('g-recaptcha-response')) {
									$this->response = form_inputEscape::simpleClean($_POST['g-recaptcha-response']);

									// If the form submission includes the "g-captcha-response" field
									// Create an instance of the service using your secret
									$resp = $recaptcha->setExpectedHostname($domain['url_domain'])
										->verify($this->response, $_SERVER['REMOTE_ADDR']);
								}
								break;
							case '3':
								if (http_request::isPost('recaptcha_response')) {
									$this->response = $_POST['recaptcha_response'];//form_inputEscape::simpleClean($_POST['recaptcha_response']);

									if (http_request::isPost('recaptcha_action')) {
										$this->action = $_POST['recaptcha_action'];//form_inputEscape::simpleClean($_POST['recaptcha_action']);
										$resp = $recaptcha->setExpectedHostname($domain['url_domain'])
											//$resp = $recaptcha
                                            ->setExpectedAction($this->action)
                                            ->setScoreThreshold(0.5)
											->verify($this->response, $_SERVER['REMOTE_ADDR']);
                                        if (!$resp->isSuccess()) {
                                            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                                            $this->logger->log('statement','error',json_encode($resp->getErrorCodes()),$this->logger::LOG_MONTH);
                                        }
									}
								}
								break;
						}

						return ($resp !== null && $resp->isSuccess());
					}
                }
            }
        }
		return false;
    }
}