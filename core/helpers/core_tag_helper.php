<?php
class CoreTagHelper {
	protected static $cached_attributes = array();
	
	public static function image_tag($image, $options = array()) {
		$options = CoreTagHelper::merge_attributes(array(
			'src' => CoreHelper::instance()->url_for('/images/' . $image),
			'alt' => $image
		), $options);
		
		return self::simple_tag('img', $options);
	}
	
	public static function javascript_include_tag() {
		$files = func_get_args();
		if(empty($files))
			$files = array('prototype', 'effects', 'application');
		
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
	
	public static function merge_attributes() {
		$sets = func_get_args();
		// $attributes = call_user_func_array('array_merge', $sets);
		
		$attributes = array();
		foreach($sets AS $options) {
			(array) $attributes += (array) $options;
		}

		return self::parse_attributes($attributes);
	}
	
	public static function parse_attributes($attributes, $wants_joined = false) {
		if(!is_array($attributes)) {
			// TODO: this is just for now
			$attributes = array();
		}
		
		$cache_key = self::generate_cache_key($attributes);
		if(isset(self::$cached_attributes[$cache_key])) {
			$attributes = self::$cached_attributes[$cache_key];
		} else {
			foreach($attributes AS $key => $value) {
				if($key == 'confirm') {
					$attributes['onclick'] = "return confirm('" . $value . "')";
					unset($attributes[$key]);
				}
				if($key == 'multipart') {
					$attributes['enctype'] = 'multipart/form-data';
					unset($attributes[$key]);
				}
				if($value === true) {
					$attributes[$key] = $key;
				}
			}
			
			self::$cached_attributes[$cache_key] = $attributes;
		}

		if($wants_joined) {
			return self::join_attributes($attributes);
		} else {
			return $attributes;
		}
	}
	
	public static function join_attributes($attributes) {
		ksort($attributes);
		$combined = '';
		
		foreach($attributes AS $key => $value) {
			$combined .= ' ' . $key . '="' . $value . '"';
		}
		
		return $combined;
	}
	
	final private static function generate_cache_key($array) {
		$cache_key = '';
		foreach($array AS $key => $value) {
			$cache_key .= $key . $value;
		}
		
		return $cache_key;
	}
	
	protected static function find_or_cache_attributes($attributes) {
		$serialized = serialize($attributes);
		$key = md5($serialized);
		if(isset(self::$cached_attributes[$key])) {
			return unserialize(self::$cached_attributes[$key]);
		} else {
			self::$cached_attributes[$key] = $serialized;
			return $attributes;
		}
	}
}