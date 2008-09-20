<?php
class CoreHelper {
	public $locals;
	
	// public function __construct($locals) {
	// 	var_dump($locals);
	// 	$this->locals = new PropertyObject($locals);
	// }
	public function __construct() {
		foreach($GLOBALS['controller'] AS $key => $value) {
			$this->locals->{$key} = $value;
		}
		// $this->locals = $GLOBALS['controller'];
	}
}