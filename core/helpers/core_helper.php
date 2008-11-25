<?php
class CoreHelper {
	private static $mixed_in = array();
	
	final public function __construct() {
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
	
	final public function pluralize($count, $word) {
		return $count . ' ' . Inflector::conditionalPlural($count, $word);
	}
		
	final public static function instance() {
		static $instance;
		if(!isset($instance)) {
			$instance = new self();
		}
		
		return $instance;
	}
	
	final public function __call($method, $arguments) {
		return self::__callStatic($method, $arguments);
	}
	
	final public static function __callStatic($method, $arguments) {
		if(isset(self::$mixed_in[$method]))
			return call_user_func_array(array(self::$mixed_in[$method], $method), $arguments);
		else
			trigger_error("Helper '{$method}' not found");
	}
}
