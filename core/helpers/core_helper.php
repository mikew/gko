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
	
	public function image_tag($image) {
		return $this->tag('img', array(
			'src' => WWW_HOME . '/images/' . $image,
			'alt' => $image
		));
	}
	
	public function stylesheet_tag($names = array('screen')) {
		if(!is_array($names))
			$names = explode(' ', $names);
		
		$html = '';
		foreach($names AS $stylesheet) {
			$html .= $this->tag('link', array(
				'rel' => 'stylesheet',
				'type' => 'text/css',
				'href' => WWW_HOME . '/stylesheets/' . $stylesheet . '.css'
			));
			$html .= "\n";
		}
		
		return $html;
	}
	
	public function tag($tag, $attributes = array(), $content = '') {
		$attributes = $this->parse_attributes($attributes);
		$constructed = '<' . $tag;
		if(!empty($attributes))
			$constructed .= $this->join_attributes($attributes, true);
		
		if(empty($content)) {
			$constructed .= ' />';
		} else {
			$constructed .= '>' . $content;
			$constructed .= '</' . $tag . '>';
		}
		
		return $constructed;
	}
	
	public function parse_attributes($attributes, $wants_joined = false) {
		if(!is_array($attributes)) {
			// TODO: this is just for now
			$attributes = array();
		}
		
		if($wants_joined) {
			return $this->join_attributes($attributes);
		} else {
			ksort($attributes);
			return $attributes;
		}
	}
	
	public function join_attributes($attributes) {
		// $combined = $with_space ? ' ' : '' ;
		ksort($attributes);
		$combined = '';
		foreach($attributes AS $key => $value) {
			$combined .= ' ' . $key . '="' . $value . '"';
		}
		
		return $combined;
	}
}