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
			'src' => $this->url_for('/images/' . $image),
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
				'href' => $this->url_for('/stylesheets/' . $stylesheet . '.css')
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
	
	public function link_to($text, $url = array(), $html_options = array()) {
		$html_options['href'] = $this->url_for($url);
		return $this->tag('a', $html_options, $text);
	}
	
	public function link_to_unless_current($text, $url = array(), $html_options = array()) {
		// TODO: url is generated twice. shouldn't have to be
		$test = $this->url_for($url);
		// print_r(array(WWW_HOME . '/' . WWW_PATH, $test));
		return $test == WWW_HOME . WWW_PATH ? $text : $this->link_to($text, $url, $html_options);
	}
	
	public function url_for($options) {
		return WWW_HOME . $GLOBALS['map']->utils->urlFor($options);
	}
	
	public function format_date($date, $format) {
		return date($format, $this->parse_datetime($date));
	}
	
	public function format_time($time, $format) {
		return $this->format_date($time, $format);
	}
	
	public function parse_datetime($test) {
		return is_int($test) ? $test : strtotime($test);
	}
	
	public function time_distance_in_words($timestamp, $options = array()) {
		$options = array_merge(array(
			'past' => 'ago',
			'join' => ' at ',
			'time' => 'g:i a',
			'date' => 'F jS'
		), $options);
		
		$timestamp = $this->parse_datetime($timestamp);
		// $basetime = $basetime === false ? time() : $this->parse_datetime($basetime);
		$basetime = time();
		$difference = $timestamp - $basetime;
		$absolute = abs($difference);
		
		$relative = array(date($options['date'], $timestamp), date($options['time'], $timestamp));
		$minutes_ago = round($absolute / 60);
		$one_hour = 60;
		$one_day = $one_hour * 24;
		$one_week = $one_day * 7;
		$fuzziness = 15;
		
		$week = date('W', $timestamp);
		$week_base = date('W', $basetime);
		$doy = date('z', $timestamp);
		$doy_base = date('z', $basetime);
		
		$quantity = '';
		$measure = '';
		// echo implode(', ', array($timestamp, $basetime, $difference, $absolute, $minutes_ago));
		if($difference < 0) {
			if($absolute < 60) {
				$relative = 'less than a minute';
			} elseif(in_array($minutes_ago, range(1, $one_hour - $fuzziness - 1))) {
				$quantity = $minutes_ago;
				$measure = 'minute';
			} elseif(in_array($minutes_ago, range($one_hour - $fuzziness, $one_hour + $fuzziness - 1))) {
				$relative = 'about 1 hour';
			} elseif($minutes_ago < $one_day) {
				if($doy != $doy_base) {
					$relative = array('yesterday', date($options['time'], $timestamp));
				} else {
					$quantity = round($minutes_ago / 60);
					$measure = 'hour';
				}
			}
		} else {
			// $relative = 'future';
		}
		
		if(is_array($relative))
			$relative = implode($options['join'], array($relative[0], $relative[1]));
		if(!empty($quantity) && !empty($measure))
			$relative = $quantity . ' ' . Inflector::conditionalPlural($quantity, $measure) . ' ' . $options['past'];
		
		return $relative;
	}
}
