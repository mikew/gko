<?php
require_once File::join(CORE_VENDOR_HOME, 'doctrine', 'lib', 'Doctrine.php');
$config = Doctrine_Parser::load(File::join(FW_HOME, 'config', 'database.yml'), 'yml');

// TODO: remove ['default'] use
Doctrine_Manager::connection($config['default']['adapter'] . ':' . FW_HOME . '/' . $config['default']['database'], 'gko');

Doctrine_Manager::getInstance()->setAttribute('model_loading', 'conservative');
Doctrine_Manager::getInstance()->setAttribute('validate', 'constraints');

unset($config);