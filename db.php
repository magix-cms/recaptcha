<?php
class plugins_recaptcha_db
{
    /**
     * @param $config
     * @param bool $params
     * @return mixed|null
     * @throws Exception
     */
    public function fetchData($config, $params = false)
    {
        $sql = '';

        if (is_array($config)) {
            if ($config['context'] === 'all') {

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;

            } elseif ($config['context'] === 'one') {
                switch ($config['type']) {
                    case 'root':
                        $sql = 'SELECT * FROM mc_recaptcha ORDER BY id_recaptcha DESC LIMIT 0,1';
                        break;
                    case 'page':
                        $sql = 'SELECT * FROM `mc_recaptcha` WHERE `id_recaptcha` = :id_recaptcha';
                        break;
                }

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
        }
    }

    /**
     * @param $config
     * @param array $params
     * @throws Exception
     */
    public function insert($config, $params = array())
    {
        if (is_array($config)) {
            $sql = '';

            switch ($config['type']) {
                case 'newConfig':

                    $sql = 'INSERT INTO mc_recaptcha (apiKey,secret,published,date_register)
		            VALUE(:apiKey,:secret,:published,NOW())';

                    break;
            }

            if($sql !== '') component_routing_db::layer()->insert($sql,$params);
        }
    }

    /**
     * @param $config
     * @param array $params
     * @throws Exception
     */
    public function update($config, $params = array())
    {
        if (is_array($config)) {
            $sql = '';

            switch ($config['type']) {
                case 'config':
                    $sql = 'UPDATE mc_recaptcha
                    SET 
                        apiKey=:apiKey,
                        secret=:secret,
                        published=:published
                    WHERE id_recaptcha=:id';
                    break;
            }

            if($sql !== '') component_routing_db::layer()->update($sql,$params);
        }
    }
}
?>