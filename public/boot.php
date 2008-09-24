<?php
$start = microtime(true);

ini_set('display_errors',1);
error_reporting(E_ALL);

define('FW_HOME', realpath(dirname(__FILE__) . '/..'));
define('APP_HOME', FW_HOME . '/application');
define('CORE_HOME', FW_HOME . '/core');

require_once FW_HOME . '/core/core.class.php';

function __autoload($class) {
	$klass = strtolower($class);
	if($klass == 'corecontroller')
		require_once File::join(CORE_HOME, 'controllers', 'core_controller.php');
	elseif(substr($klass, -10) == 'controller')
		require_once File::join(APP_HOME, 'controllers', String::underscore($class) . '.php');
	if($klass == 'corehelper')
		require_once File::join(CORE_HOME, 'helpers', 'core_helper.php');
	elseif(substr($klass, -6) == 'helper')
		require_once File::join(APP_HOME, 'helpers', String::underscore($class) . '.php');
}

function clear_end_slash($from) {
	return substr($from, -1) == '/' ? substr($from, 0, -1) : $from ;
}

$path = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
$path = substr($path, strlen(str_replace(basename(__FILE__), '', $_SERVER['SCRIPT_NAME'])));
define('WWW_HOME', clear_end_slash(str_replace(basename(__FILE__), '', $_SERVER['SCRIPT_NAME'])));

$keywords = explode('/', preg_replace(array('/^\//', '/\/$/'), '', $path));
define('CONTROLLER', !empty($keywords[0]) ? $keywords[0] : 'welcome');
define('ACTION', !empty($keywords[1]) ? $keywords[1] : 'index');

unset($keywords);
unset($path);

$controller = CONTROLLER . 'Controller';
$controller = new $controller();
$controller->run();

$finish = microtime(true);
$duration = $finish - $start;
echo "generated in {$duration} seconds";
?>
