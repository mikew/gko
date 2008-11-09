<?php
class CoreHelper {
	public static $locals;
	private static $mixed_in = array();
	
	// final public static function construct() {
	// 	foreach($GLOBALS['controller'] AS $key => $value) {
	// 		self::$locals->{$key} = $value;
	// 	}
	// }
	final public function __construct() {
		foreach($GLOBALS['controller'] AS $key => $value) {
			self::$locals->{$key} = $value;
		}
		
		self::mixin('CoreFormHelper', 'CoreTagHelper', 'CoreTimeHelper', 'CoreURLHelper', CONTROLLER . 'Helper');
	}
	
	final public static function mixin() {
		$args = func_get_args();
		$args = Core::interpret_options($args);
		foreach($args AS $helper) {
			foreach(get_class_methods($helper) AS $method) {
				self::$mixed_in[$method] = $helper;
			}
		}
	}
	
	// final public static function register() {
	final public static function instance() {
		static $instance;
		if(!isset($instance)) {
			$instance = new self();
		}
		
		return $instance;
		// if(!class_exists('Helpers', false)) {
		// 	$klass = CONTROLLER . 'Helper';
		// 	eval('class Helpers extends ' . $klass . ' {}');
		// 	Helpers::construct();
		// }
	}
	
	final public function __call($method, $arguments) {
		return self::__callStatic($method, $arguments);
	}
	
	final public static function __callStatic($method, $arguments) {
		return call_user_func_array(array(self::$mixed_in[$method], $method), $arguments);
	}
}
