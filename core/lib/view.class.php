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
		$file = self::interpret_file($file)->{$is_partial ? 'absolute_as_partial' : 'absolute'};
		
		$file .= substr($file, -strlen($extension)) == $extension ? '' : $extension;
	
		return File::exists($file) ? $file : false ;
	}
	
	protected static function interpret_file($examine) {
		if(substr($examine, 0, 1) != '/') {
			if(strpos($examine, '/') === false)
				$examine = File::join(String::underscore(CONTROLLER), $examine);
			
			$examine = File::join(APP_HOME, 'views', $examine);
		}
		
		$parts = explode(DIRECTORY_SEPARATOR, $examine);
		
		$name = array_pop($parts);
		$partial = '_' . $name;
		$base_path = File::join($parts);
		
		return new _H(array(
			'name' => $name,
			'name_as_partial' => $partial,
			'absolute' => File::join($base_path, $name),
			'absolute_as_partial' => File::join($base_path, $partial)
		));
	}
	
	final public static function render($file, $mime = '') {
		$context = CoreContext::instance();
		$mime = CoreMime::interpret($mime);
		$file = self::find_file($file, $mime);
		
		$contents = '';
		if(!empty($file)) {
			$callback = 'render_file_' . $mime;
			
			if(method_exists('CoreView', $callback)) {
				$contents = call_user_func(array('self', $callback), $file, $context);
			} else {
				ob_start();
				include $file;
				$contents = ob_get_contents();
				ob_end_clean();
			}
		}
		
		return $contents;
	}
	
	protected static function render_file_rss($file, $context) {
		$feed = new RSSFeed();
		include $file;
		return $feed;
	}
	
	protected static function render_file_markdown($file) {
		return Markdown(File::read($file));
	}
	
	protected static function render_partial($file, $data = false, $locals = array()) {
		$context = CoreContext::instance();
		$contents = '';
		
		foreach($locals AS $key => $value) {
			${$key} = $value;
		}
		
		$interpreted = self::interpret_file($file);
		$name = $interpreted->name;
		$plural = Inflector::pluralize($name);

		if($data === false) {
			if(isset($context->{$plural}))
				$data = $context->{$plural};
			elseif(isset($context->{$name}))
				$data = array($context->{$name});
			else
				$data = array($name);
		}
		
		$file = self::find_file($file, '', true);
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