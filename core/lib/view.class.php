<?php
class CoreView {
	protected static $locals = array();
	
	public static function initialize() {
		foreach($GLOBALS['controller'] AS $key => $value) {
			self::$locals[$key] = $value;
		}
	}
	
	protected static function find_file($file, $mime = '', $is_partial = false) {
		$file = File::join($file);

		$extension = CoreMime::extension($mime);
		if(substr($file, 0, 1) != '/') {
			$parts = explode('/', $file);
			if($is_partial) {
				$real = array_pop($parts);
				array_push($parts, '_' . $real);
			}
			
			$view_path = File::join(APP_HOME, 'views');
			if(count($parts) == 1)
				$view_path = File::join($view_path, String::underscore(CONTROLLER));
			
			$file = File::join($view_path, File::join($parts));
		}
		
		$file .= substr($file, -strlen($extension)) == $extension ? '' : $extension;
	
		return File::exists($file) ? $file : false ;
	}
	
	final public static function render($file, $mime = '') {
		$helpers = CoreHelper::instance();
		$mime = CoreMime::interpret($mime);
		$file = self::find_file($file, $mime);
		
		$contents = '';
		if(!empty($file)) {
			$callback = 'render_file_' . $mime;
			
			// THIS WILL BE REPEATED A LOT. UGH
			foreach(self::$locals AS $key => $value) {
				${'__' . $key} = $value;
			}
			
			if(method_exists('CoreView', $callback)) {
				$contents = call_user_func(array('self', $callback), $file, $helpers);
			} else {
				ob_start();
				include $file;
				$contents = ob_get_contents();
				ob_end_clean();
			}
		}
		
		return $contents;
	}
	
	protected static function render_file_rss($file, $helpers) {
		foreach(self::$locals AS $key => $value) {
			${'__' . $key} = $value;
		}
		
		$feed = new RSSFeed();
		include $file;
		return $feed;
	}
	
	protected static function render_file_markdown($file) {
		return Markdown(File::read($file));
	}
	
	protected static function render_partial($name, $data = array(), $locals = array()) {
		$helpers = CoreHelper::instance();
		$contents = '';
		
		foreach(self::$locals AS $key => $value) {
			${'__' . $key} = $value;
		}
		
		foreach($locals AS $key => $value) {
			${$key} = $value;
		}
		
		if(!empty($data)) {
			if(!isset($data[0]))
				$data = array($data);
		} else {
			$data = array($name);
		}
		
		$file = self::find_file($name, '', true);
		${$name . '_counter'} = 0;
		foreach($data AS $object) {
			${$name} = $object;
			
			ob_start();
			include $file;
			$contents .= ob_get_contents();
			ob_end_clean();
			
			${$name . '_counter'}++;
		}
		
		return $contents;
	}
}