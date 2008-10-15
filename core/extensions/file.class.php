<?php
class File {
	public static function join() {
		$args = func_get_args();
		return Core::join_paths($args);
	}
	
	public static function write($file, $data) {
		$pointer = fopen($file, 'w');
		fwrite($pointer, $data);
		fclose($pointer);
	}
}