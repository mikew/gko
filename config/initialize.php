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
	elseif(substr($klass, -7) == 'adapter')
		require_once File::join(CORE_HOME, 'adapters', String::underscore($class) . '.php');
	elseif(substr($klass, -6) == 'helper')
		require_once File::join(APP_HOME, 'helpers', String::underscore($class) . '.php');
	elseif(substr($klass, 0, 8) == 'doctrine')
		Doctrine::autoload($class);
}

require_once File::join(CORE_VENDOR_HOME, 'Doctrine', 'lib/', 'Doctrine.php');
