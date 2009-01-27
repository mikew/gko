<?php
final class Core {
	public static function join_paths() {
		$args = func_get_args();
		$args = self::interpret_options($args);
		return implode(DIRECTORY_SEPARATOR, $args);
	}
	
	public static function interpret_options($args) {
		// TODO: eventually all user-passed options should go through this function.
		// therefore, we need to do more checks (
		// "id=promo size=300x120" => { id => promo, width => 300, height=>120 }
		// )
		return is_array($args[0]) ? $args[0] : $args ;
	}
	
	public static function router() {
		return CoreRouter::instance();
	}
	
	public static function context() {
		return CoreContext::instance();
	}
	
	public static function helpers() {
		return CoreHelper::instance();
	}
	
	public static function get_methods($of) {
		return get_class_methods($of);
	}
	
 	public static function cascade_method($object, $method, $args = array()) {
		$classes = array_values(class_parents($object));
		array_unshift($classes, get_class($object));
		$results = array();
		foreach(array_reverse($classes) AS $class) {
			try {
				$reflection = new ReflectionMethod($class, $method);
				if($class === $reflection->getDeclaringClass()->getName()) {
					call_user_func_array(array($class, $method), $args);
				}
			} catch(ReflectionException $e) {
				// nothing to do. exception is thrown when the class doesn't
				// have the method we're looking for, which is a good thing
			}
		}
		
		return $results;
	}
	
	public static function autoload($class) {
		$klass = strtolower($class);

		if($klass == 'corecontroller') {
			require_once File::join(CORE_HOME, 'controllers', 'core_controller.php');
		} elseif($klass == 'coreview') {
			require_once File::join(CORE_LIB_HOME, 'view.class.php');
		} elseif($klass == 'corecontext') {
			require_once File::join(CORE_LIB_HOME, 'context.class.php');
		} elseif($klass == 'coreflash') {
			require_once File::join(CORE_LIB_HOME, 'flash.class.php');
		} elseif($klass == 'cordial') {
			require_once File::join(CORE_LIB_HOME, 'cordial.class.php');
		} elseif(substr($klass, -10) == 'controller') {
			require_once File::join(APP_HOME, 'controllers', String::underscore($class) . '.php');
		} elseif(substr($klass, 0, 4) == 'core' && substr($klass, -6) == 'helper') {
			require_once File::join(CORE_HOME, 'helpers', String::underscore($class) . '.php');
		} elseif(substr($klass, -6) == 'helper') {
			require_once File::join(APP_HOME, 'helpers', String::underscore($class) . '.php');
		} elseif(substr($klass, 0, 8) == 'doctrine') {
			Doctrine::autoload($class);
		} elseif(substr($klass, 0, 4) == 'base') {
			require_once File::join(APP_HOME, 'models', 'generated', $class . '.php');
		} elseif(substr($klass, 0, 12) == 'horde_routes') {
			$parts = explode('_', $class);
			require_once File::join(CORE_VENDOR_HOME, 'routes', $parts[2] . '.php');
		}
		else {
			$model = File::join(APP_HOME, 'models', $class . '.php');
			$lib = File::join(LIB_HOME, String::underscore($class) . '.php');

			if(File::exists($model))
				require_once $model;
			elseif(File::exists($lib))
				require_once $lib;
		}
	}
}

define('FW_HOME', realpath(Core::join_paths(dirname(__FILE__), '../..')));
define('APP_HOME', Core::join_paths(FW_HOME, 'application'));
define('CORE_HOME', Core::join_paths(FW_HOME, 'core'));
define('CORE_VENDOR_HOME', Core::join_paths(CORE_HOME, 'vendor'));
define('CORE_LIB_HOME', Core::join_paths(CORE_HOME, 'lib'));
define('VENDOR_HOME', Core::join_paths(FW_HOME, 'vendor'));
define('CONFIG_HOME', Core::join_paths(FW_HOME, 'config'));
define('TMP_HOME', Core::join_paths(FW_HOME, 'tmp'));
define('LIB_HOME', Core::join_paths(FW_HOME, 'lib'));

# Constants for Doctrine
define('FIXTURE_PATH', Core::join_paths(FW_HOME, 'db', 'fixtures'));
define('MODEL_PATH', Core::join_paths(APP_HOME, 'models'));
define('MIGRATIONS_PATH', Core::join_paths(FW_HOME, 'db', 'migrations'));
define('SCHEMA_PATH', Core::join_paths(FW_HOME, 'db', 'schema'));
define('SQL_PATH', Core::join_paths(FW_HOME, 'db', 'sql'));

require_once Core::join_paths(CORE_HOME, 'extensions', 'propertyobject.class.php');
require_once Core::join_paths(CORE_HOME, 'extensions', 'file.class.php');
require_once Core::join_paths(CORE_HOME, 'extensions', 'string.class.php');
require_once Core::join_paths(CORE_HOME, 'extensions', 'time.class.php');
