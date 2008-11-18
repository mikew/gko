<?php
class CoreFormHelper {
	public static function form_tag($url = array(), $method = 'post', $multipart = false) {
		$simulate = CoreMime::should_simulate_post($method);
		
		$attributes = CoreHelper::instance()->parse_attributes(array(
			'method' => $simulate ? 'post' : $method,
			'action' => CoreHelper::instance()->url_for($url)
		), true);
		
		$html =  '<form' . $attributes . '>';
		if($simulate)
			$html .= self::hidden_field('_method', $method);
		
		return $html;
	}
	
	public static function form_tag_for($parts, $multipart = false, $attributes = array()) {
		if(!is_array($parts))
			$parts = array($parts);
		
		$object = array_pop($parts);
		
		$key = 'id';
		if(is_string($object)) {
			$key = $object;
			$object = array_pop($parts);
		}
		
		$action = $object->exists() ? 'update' : 'create';
		$class = $object->exists() ? Inflector::singularize(get_class($object)) : get_class($object);
		array_push($parts, $class);
		
		$path_name = $action . '_' . implode('/', $parts);
		
		$path_name = String::lowercase($path_name);
		
		return CoreHelper::instance()->form_tag(array($path_name, array('id' => $object->{$key})), $object->exists() ? 'put' : 'post', $multipart);
	}
	
	public static function end_form_tag() {
		return '</form>';
	}
	
	public static function text_field($object, $key, $attributes = array()) {
		return CoreHelper::instance()->simple_tag('input', array_merge($attributes, array(
			'type' => 'text',
			'name' => self::interpret_pair($object, $key, 'name'),
			'id' => self::interpret_pair($object, $key, 'id'),
			'value' => self::interpret_pair($object, $key)
		)));
	}
	
	public static function text_area($object, $key, $attributes = array()) {
		$attributes = array_merge(array(
			'cols' => 60,
			'rows' => 10
		), $attributes, array(
			'name' => self::interpret_pair($object, $key, 'name'),
			'id' => self::interpret_pair($object, $key, 'id')
		));
		
		return CoreHelper::instance()->tag('textarea', $attributes, self::interpret_pair($object, $key));
	}
	
	public static function hidden_field($name, $value) {
		return CoreHelper::instance()->simple_tag('input', array(
			'type' => 'hidden',
			'name' => $name,
			'value' => $value
		));
	}
	
	public static function submit_button($value = 'Continue') {
		return CoreHelper::instance()->simple_tag('input', array(
			'type' => 'submit',
			'value' => $value
		));
	}
	
	private static function interpret_pair($object, $key, $as = 'data') {
		switch($as) {
			case 'id':
				return $object . '_' . $key;
				break;
			case 'name':
				return $object . '[' . $key . ']';
				break;
			case 'data':
			default:
				return CoreContext::get($object)->{$key};
				break;
		}
	}
}