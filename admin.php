<?php
require_once ('db.php');
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The tabspanelContent management system optimized for users
 # Copyright (C) 2008 - 2021 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Aurelien Gerits (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
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
 * @category plugins
 * @package recaptcha
 * @copyright  MAGIX CMS Copyright (c) 2011 - 2013 Gerits Aurelien, http://www.magix-dev.be, http://www.magix-cms.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 2.0
 * @create 26-08-2011
 * @Update 12-04-2021
 * @author Gérits Aurélien <contact@magix-dev.be>
 * @name plugins_recaptcha_admin
 */
class plugins_recaptcha_admin extends plugins_recaptcha_db {
	/**
	 * @var backend_model_template $template
	 * @var backend_controller_plugins $plugins
	 * @var backend_model_data $data
	 * @var component_core_message $message
	 * @var backend_model_language $modelLanguage
	 * @var component_collections_language $collectionLanguage
	 * @var backend_controller_domain $domain
	 * @var component_routing_url $routingUrl
	 */
	protected backend_model_template $template;
	protected backend_controller_plugins $plugins;
	protected backend_model_data $data;
	protected component_core_message $message;
	protected backend_model_language $modelLanguage;
	protected component_collections_language $collectionLanguage;
	protected backend_controller_domain $domain;
	protected component_routing_url $routingUrl;

    /**
     * @var string $lang ,
     * @var string $action
     * @var string $tab
     */
    public string
		$lang,
		$action,
		$tab;

    /**
     * @var int $edit
     */
    public int $edit;

    /**
     * @var array $recaptchaData
     */
    public array $recaptchaData;

	/**
	 *
	 */
    public function __construct() {
        $this->template = new backend_model_template();
        $this->plugins = new backend_controller_plugins();
        $this->message = new component_core_message($this->template);
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->data = new backend_model_data($this);
        $this->domain = new backend_controller_domain();
        $this->routingUrl = new component_routing_url();
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string|null $context
     * @param bool|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = null, ?string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * Method to override the name of the plugin in the admin menu
     * @return string
     */
    public function getExtensionName(): string {
        return $this->template->getConfigVars('recaptcha_plugin');
    }

    /**
     *
     */
    public function run(){
		if (http_request::isGet('edit')) $this->edit = form_inputEscape::numeric($_GET['edit']);
		if (http_request::isGet('tabs')) $this->tab = form_inputEscape::simpleClean($_GET['tabs']);
		if (http_request::isRequest('action')) $this->action = form_inputEscape::simpleClean($_REQUEST['action']);

		$config = $this->getItems('config',NULL,'one',false);

        if(http_request::isMethod('POST') && !empty($this->action)) {
			$status = false;
			$type = 'error';

			if (http_request::isPost('recaptchaData')) $this->recaptchaData = form_inputEscape::arrayClean($_POST['recaptchaData']);

            if($this->action === 'edit' && !empty($this->recaptchaData)) {
				$newData = [
					'apiKey' => $this->recaptchaData['apiKey'],
					'secret' => $this->recaptchaData['secret'],
					'version' => $this->recaptchaData['version'],
					'published' => (!isset($this->recaptchaData['published']) ? 0 : 1)
				];

				if($config['id_recaptcha']){
					$newData['id'] = $config['id_recaptcha'];
					parent::update('config',$newData);
				}
				else {
					parent::insert('config',$newData);
				}
				$status = true;
				$type = 'update';
            }
			$this->message->json_post_response($status, $type);
        }
		else {
            $this->template->assign('page', $config);
            $this->template->display('index.tpl');
        }
    }
}
