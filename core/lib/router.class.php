<?php
class CoreRouter {
	static public $will_redirect = false;
	// static private $map;
	
	static public function instance() {
		static $map;
		if(!isset($map)) {
			$map = new Horde_Routes_Mapper(array(
				'controllerScan' => 'CoreRouter::scan_controllers'
			));
			$map->environ = $_SERVER;
			$map->environ['SCRIPT_NAME'] = WWW_HOME;
		}
		
		return $map;
	}
	
	static public function scan_controllers() {
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
	
	static public function clear_end_slash($from) {
		return substr($from, -1) == '/' ? substr($from, 0, -1) : $from ;
	}
	
	static public function redirect_to($options) {
		if(!is_array($options))
			$options = array($options, array());
		
		$options[1]['qualified'] = true;
		$url = self::url_for($options);
		CoreMime::reset_headers();
		CoreMime::set_header('Location', $url);
	}
	
	static public function url_for($options = array()) {
		if(is_array($options)) {
			if(isset($options[0]))
				$url = self::instance()->utils->urlFor($options[0], $options[1]);
			else
				$url = self::instance()->utils->urlFor($options);
		} else {
			$url = self::instance()->utils->urlFor($options);
		}
		
		return empty($options) ? WWW_HOME . WWW_PATH : $url;
	}
}