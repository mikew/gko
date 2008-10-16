<?php
// require_once CORE_VENDOR_HOME . '/spyc/spyc.php5';
// $db_config = Spyc::YAMLLoad(CONFIG_HOME . '/database.yml');
// $db_config = $db_config['default'];
// $klass = $db_config['adapter'] . 'Adapter';
// $db = new $klass($db_config);
// unset($db_config);
// unset($klass);

// define('DSN', 'sqlite:///' . DB_PATH);
// $yml = new Doctrine_Parser_sfYaml();
$config = Doctrine_Parser::load(File::join(FW_HOME, 'config', 'database.yml'), 'yml');

// TODO: remove ['default'] use
Doctrine_Manager::connection($config['default']['adapter'] . ':' . FW_HOME . '/' . $config['default']['database'], 'gko');
Doctrine_Manager::getInstance()->setAttribute('model_loading', 'conservative');

unset($config);
// unset($yml);