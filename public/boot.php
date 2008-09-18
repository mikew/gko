<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

define('FW_HOME', realpath(dirname(__FILE__) . '/..'));

require_once FW_HOME . '/core/file.class.php';
require_once FW_HOME . '/core/string.class.php';

function __autoload($class) {
	$klass = strtolower($class);
	if($klass == 'basecontroller')
		require_once File::join(FW_HOME, 'core', 'base_controller.php');
	elseif(substr($klass, -10) == 'controller') {
		require_once File::join(FW_HOME, 'controllers', String::underscore($class) . '.php');
	}
}

$path = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
$keywords = explode('/', preg_replace(array('/^\//', '/\/$/'), '', $path));

define('CONTROLLER', !empty($keywords[0]) ? $keywords[0] : 'welcome');
define('ACTION', !empty($keywords[1]) ? $keywords[1] : 'index');

$controller = CONTROLLER . 'Controller';
$controller = new $controller();
$controller->render();

unset($keywords);
unset($path);
?>