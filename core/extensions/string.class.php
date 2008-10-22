<?php
class String {
	public function underscore($word) {
		$word = str_replace('_', '/', $word);
		return Inflector::underscore($word);
	}
}