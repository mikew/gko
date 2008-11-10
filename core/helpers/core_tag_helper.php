<?php
class CoreTagHelper {
	public static function image_tag($image) {
		return self::simple_tag('img', array(
			'src' => CoreHelper::instance()->url_for('/images/' . $image),
			'alt' => $image
		));
	}
	
	public static function javascript_include_tag() {
		$files = func_get_args();
		if(empty($files))
			$files = array('prototype', 'effects');
		
		$html = '';
		foreach($files AS $file) {
			$html .= self::tag('script', array(
				'type' => 'text/javascript',
				'src' => CoreHelper::instance()->url_for('/javascripts/' . $file . '.js')
			));
			$html .= "\n";
		}
		
		return $html;
	}
	
	public static function stylesheet_tag() {
		$names = func_get_args();
		if(empty($names))
			$names = array('screen');
		
		$html = '';
		foreach($names AS $stylesheet) {
			$html .= self::simple_tag('link', array(
				'rel' => 'stylesheet',
				'type' => 'text/css',
				'href' => CoreHelper::instance()->url_for('/stylesheets/' . $stylesheet . '.css')
			));
			$html .= "\n";
		}
		
		return $html;
	}
	
	public static function rss_tag($title = 'RSS Feed', $url_options) {
		return self::simple_tag('link', array(
			'title' => $title,
			'rel' => 'alternate',
			'type' => 'application/rss+xml',
			'href' => CoreHelper::instance()->url_for($url_options)
		)) . "\n";
	}
	
	public static function tag($tag, $attributes = array(), $content = '') {
		$constructed = '<' . $tag;
		$constructed .= self::parse_attributes($attributes, true);
		$constructed .= '>' . $content;
		$constructed .= '</' . $tag . '>';
		
		return $constructed;
	}
	
	public static function simple_tag($tag, $attributes = array()) {
		$constructed = '<' . $tag;
		$constructed .= self::parse_attributes($attributes, true);
		$constructed .= ' />';
		
		return $constructed;
	}
	
	public static function parse_attributes($attributes, $wants_joined = false) {
		if(!is_array($attributes)) {
			// TODO: this is just for now
			$attributes = array();
		}
		
		if($wants_joined) {
			return self::join_attributes($attributes);
		} else {
			ksort($attributes);
			return $attributes;
		}
	}
	
	public static function join_attributes($attributes) {
		// $combined = $with_space ? ' ' : '' ;
		ksort($attributes);
		$combined = '';
		foreach($attributes AS $key => $value) {
			$combined .= ' ' . $key . '="' . $value . '"';
		}
		
		return $combined;
	}
}