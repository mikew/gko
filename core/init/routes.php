<?php
require_once File::join(CORE_LIB_HOME, 'router.class.php');

define('WWW_HOME', CoreRouter::clear_end_slash(str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['SCRIPT_NAME'])));
$path = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
$path =  substr($path, strlen(WWW_HOME));
define('WWW_PATH', $path);
unset($path);

$map = CoreRouter::instance();
include File::join(CONFIG_HOME, 'routes.php');

$map->createRegs();
unset($map);