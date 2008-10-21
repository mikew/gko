<?php
function scan_controllers() {
	$controllers = array();
	$directory = File::join(APP_HOME, 'controllers');
	
	$files = new RecursiveDirectoryIterator(File::join(APP_HOME, 'controllers'));
	foreach($files AS $file) {
		if(!$file->isFile())
			continue;
		
		$controller = basename($file, '.php');
		$controller = str_replace('_controller', '', $controller);
		
		array_push($controllers, $controller);
	}
	
	$callback = array('Horde_Routes_Utils', 'longestFirst');
	usort($controllers, $callback);

	return $controllers;
}

function clear_end_slash($from) {
	return substr($from, -1) == '/' ? substr($from, 0, -1) : $from ;
}

define('WWW_HOME', clear_end_slash(str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['SCRIPT_NAME'])));
$path = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
$path =  substr($path, strlen(WWW_HOME));
define('WWW_PATH', $path);
unset($path);

$map = new Horde_Routes_Mapper(array(
	'controllerScan' => 'scan_controllers'
));
$map->environ = $_SERVER;
$map->environ['SCRIPT_NAME'] = WWW_HOME;

include File::join(CONFIG_HOME, 'routes.php');

$map->createRegs();
