<?php
function scan_controllers() {
	$controllers = array();
	$directory = File::join(APP_HOME, 'controllers');
	
	$files = new RecursiveDirectoryIterator(File::join(APP_HOME, 'controllers'));
	foreach($files->getChildren() AS $file) {
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

$map = new Horde_Routes_Mapper(array(
	'controllerScan' => 'scan_controllers'
));

include File::join(CONFIG_HOME, 'routes.php');

$map->createRegs();
