<?php
function clear_end_slash($from) {
	return substr($from, -1) == '/' ? substr($from, 0, -1) : $from ;
}

define('WWW_HOME', clear_end_slash(str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['SCRIPT_NAME'])));
$path = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
$path =  substr($path, strlen(WWW_HOME));

$matched = $map->match($path);

define('CONTROLLER', $matched['controller']);
define('ACTION', $matched['action']);

unset($path);

$controller = CONTROLLER . 'Controller';
$controller = new $controller();
$controller->run();
