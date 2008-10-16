<?php
$start = microtime(true);

require_once '../core/initialize.php';

function clear_end_slash($from) {
	return substr($from, -1) == '/' ? substr($from, 0, -1) : $from ;
}

$map = new Horde_Routes_Mapper();
include File::join(CONFIG_HOME, 'routes.php');
// print_r($map->match('/news/sync'));

// $m = new Horde_Routes_Mapper();
// // $m->createRegs('news', 'about');
// $m->connect('home', '/', array('controller'=>'welcome', 'action'=>'index'));
// $m->connect('/news/archive/:key', array(
// 	'controller' => 'news',
// 	'action' => 'archive'
// ));
// $m->connect(':controller/:action/:id');

// $m = new Horde_Routes_Mapper();
// $m->connect(':controller/:action/:id', 
//   array('action'=>'view', 'id'=>4));
// 
// print_r($m->match('/'));

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
