<?php
class CoreURLHelper {
	public static function link_to($text, $url = array(), $html_options = array()) {
		$html_options['href'] = self::url_for($url);
		return CoreHelper::instance()->tag('a', $html_options, $text);
	}
	
	public static function link_to_unless_current($text, $url = array(), $html_options = array()) {
		// TODO: url is generated twice. shouldn't have to be
		$test = self::url_for($url);
		return $test == self::url_for() ? $text : self::link_to($text, $url, $html_options);
	}
	
	public static function url_for($options = array()) {
		return CoreRouter::url_for($options);
	}
}