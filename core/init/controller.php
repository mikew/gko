<?php
$matched = CoreRouter::instance()->match(WWW_PATH);

foreach($matched AS $key => $value) {
	$_GET[$key] = $value;
}
if(empty($_GET['format']))
	$_GET['format'] = 'html';

CoreMime::set($_GET['format']);

define('CONTROLLER', str_replace('/', '_', $matched['controller']));
define('ACTION', $matched['action']);

$controller = CONTROLLER . 'Controller';
$controller = new $controller();
$controller->run();
