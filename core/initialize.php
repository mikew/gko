<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

ini_set('include_path', implode(':', array(
	'.',
	realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..')
)));

require_once 'core/core.class.php';

function __autoload($class) {
	$klass = strtolower($class);
	if($klass == 'corecontroller')
		require_once File::join(CORE_HOME, 'controllers', 'core_controller.php');
	elseif(substr($klass, -10) == 'controller')
		require_once File::join(APP_HOME, 'controllers', String::underscore($class) . '.php');
	elseif($klass == 'corehelper')
		require_once File::join(CORE_HOME, 'helpers', 'core_helper.php');
	elseif(substr($klass, -6) == 'helper')
		require_once File::join(APP_HOME, 'helpers', String::underscore($class) . '.php');
	elseif(substr($klass, 0, 8) == 'doctrine')
		Doctrine::autoload($class);
	elseif(substr($klass, 0, 4) == 'base')
		require_once File::join(APP_HOME, 'models', 'generated', $class . '.php');
	elseif(substr($klass, 0, 12) == 'horde_routes') {
		$parts = explode('_', $class);
		require_once File::join(CORE_VENDOR_HOME, 'routes', $parts[2] . '.php');
	}
	else
		require_once File::join(APP_HOME, 'models', $class . '.php');
}

require_once File::join(CORE_VENDOR_HOME, 'php-markdown-extra', 'markdown.php');

require_once File::join(CORE_VENDOR_HOME, 'doctrine', 'lib', 'Doctrine.php');
$config = Doctrine_Parser::load(File::join(FW_HOME, 'config', 'database.yml'), 'yml');

// TODO: remove ['default'] use
Doctrine_Manager::connection($config['default']['adapter'] . ':' . FW_HOME . '/' . $config['default']['database'], 'gko');
Doctrine_Manager::getInstance()->setAttribute('model_loading', 'conservative');

unset($config);