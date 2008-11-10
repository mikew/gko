<?php
class String {
	public static function underscore($word) {
		$word = str_replace('_', '/', $word);
		return Inflector::underscore($word);
	}
	
	public static function uppercase($string) {
		return strtoupper($string);
	}
	
	public static function lowercase($string) {
		return strtolower($string);
	}
}