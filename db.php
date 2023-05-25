<?php
class plugins_recaptcha_db {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
	public function fetchData(array $config,array $params = []) {
		if ($config['context'] === 'all') {

			/*try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}*/
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'root':
					$query = 'SELECT * FROM mc_recaptcha ORDER BY id_recaptcha DESC LIMIT 0,1';
					break;
				case 'page':
					$query = 'SELECT * FROM `mc_recaptcha` WHERE `id_recaptcha` = :id_recaptcha';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
    }

    /**
     * @param array $config
     * @param array $params
     * @return bool|string
     */
    public function insert(array $config, array $params = []) {
		switch ($config['type']) {
			case 'newConfig':

				$query = 'INSERT INTO mc_recaptcha (apiKey,secret,version,published,date_register)
				VALUE(:apiKey,:secret,:version,:published,NOW())';

				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

    /**
     * @param array $config
     * @param array $params
     * @return bool|string
     */
    public function update(array $config, array $params = []) {
		switch ($config['type']) {
			case 'config':
				$query = 'UPDATE mc_recaptcha
				SET 
					apiKey=:apiKey,
					secret=:secret,
					version=:version,
					published=:published
				WHERE id_recaptcha=:id';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }
}