<?php
class String {
	public function underscore($word) {
		// $word = preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1_\2', $word);
		// $word = preg_replace('/([a-z\d])([A-Z])/', '\1_\2', $word);
		// $word = str_replace('::', '/', $word);
		// 
		// return strtolower($word);
		
		return Inflector::underscore($word);
	}
}