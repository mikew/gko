<?php
class CoreTimeHelper {
	public static function format_date($date, $format) {
		return date($format, self::parse_datetime($date));
	}
	
	public static function format_time($time, $format) {
		return self::format_date($time, $format);
	}
	
	public static function parse_datetime($test) {
		return is_numeric($test) ? $test : strtotime($test);
	}
	
	public static function time_distance_in_words($timestamp, $options = array()) {
		$options = array_merge(array(
			'past' => 'ago',
			'join' => ' at ',
			'time' => 'g:i a',
			'date' => 'F jS'
		), $options);
		
		$timestamp = self::parse_datetime($timestamp);
		// $basetime = $basetime === false ? time() : self::parse_datetime($basetime);
		$basetime = Time::now();
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