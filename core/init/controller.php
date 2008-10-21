<?php
$matched = $map->match(WWW_PATH);

foreach($matched AS $key => $value) {
	$_GET[$key] = $value;
}

if(empty($_GET['format']))
	$_GET['format'] = 'html';

define('CONTROLLER', $matched['controller']);
define('ACTION', $matched['action']);

$controller = CONTROLLER . 'Controller';
$controller = new $controller();
$controller->run();
