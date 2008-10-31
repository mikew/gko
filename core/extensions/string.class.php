<?php
class String {
	public static function underscore($word) {
		$word = str_replace('_', '/', $word);
		return Inflector::underscore($word);
	}
}