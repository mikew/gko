<?php
class CoreFormHelper {
	public static function form_tag($url = array(), $method = 'post', $options = array()) {
		$simulate = CoreMime::should_simulate_post($method);

		$attributes = CoreTagHelper::merge_attributes($options, array(
			'method' => $simulate ? 'post' : $method,
			'action' => CoreHelper::instance()->url_for($url)
		));
		
		$html =  '<form' . CoreHelper::instance()->join_attributes($attributes) . '>';
		if($simulate) {
			$html .= self::hidden_field('_method', $method);
		}
		
		return $html;
	}
	
	public static function form_tag_for($parts, $options = array(), &$object_reference = false) {
		if(!is_array($parts)) {
			$parts = array($parts);
		}
			
		$object = array_pop($parts);
		
		$key = 'id';
		if(is_string($object)) {
			$key = $object;
			$object = array_pop($parts);
		}
		
		if($object_reference !== false) {
			$object_reference = new CoreFormBuilder(get_class($object));
		}
		
		$action = $object->exists() ? 'update' : 'create';
		$class = $object->exists() ? get_class($object) : Inflector::pluralize(get_class($object));
		array_push($parts, $class);
		
		$path_name = $action . '_' . implode('/', $parts);
		$path_name = String::lowercase($path_name);
		
		return CoreHelper::instance()->form_tag(array($path_name, array('id' => $object->{$key})), $object->exists() ? 'put' : 'post', $options);
	}
	
	public static function end_form_tag() {
		return '</form>';
	}

	public static function label($object, $key, $text = '', $attributes = array()) {
		if(empty($text))
			$text = Inflector::titleize($key);

		return CoreHelper::instance()->tag('label', CoreTagHelper::merge_attributes($attributes, array(
			'for' => self::interpret_pair($object, $key, 'id')
		)), $text);
	}
	
	public static function text_field($object, $key, $attributes = array()) {
		return CoreHelper::instance()->simple_tag('input', CoreTagHelper::merge_attributes($attributes, array(
			'type' => 'text',
			'name' => self::interpret_pair($object, $key, 'name'),
			'id' => self::interpret_pair($object, $key, 'id'),
			'value' => self::interpret_pair($object, $key)
		)));
	}
	
	public static function text_area($object, $key, $attributes = array()) {
		$attributes = CoreTagHelper::merge_attributes(array(
			'cols' => 60,
			'rows' => 10
		), $attributes, array(
			'name' => self::interpret_pair($object, $key, 'name'),
			'id' => self::interpret_pair($object, $key, 'id')
		));
		
		return CoreHelper::instance()->tag('textarea', $attributes, self::interpret_pair($object, $key));
	}
	
	public static function password_field($object, $key, $attributes = array()) {
		return CoreHelper::instance()->simple_tag('input', CoreTagHelper::merge_attributes($attributes, array(
			'type' => 'password',
			'name' => self::interpret_pair($object, $key, 'name'),
			'id' => self::interpret_pair($object, $key, 'id'),
			'value' => self::interpret_pair($object, $key)
		)));
	}
	
	public static function confirm_password_field($object, $key, $attributes = array()) {
		return CoreHelper::instance()->simple_tag('input', CoreTagHelper::merge_attributes($attributes, array(
			'type' => 'password',
			'name' => self::interpret_pair($object, $key . '_confirmation', 'name'),
			'id' => self::interpret_pair($object, $key . '_confirmation', 'id'),
			'value' => CoreMime::is_get() ? self::interpret_pair($object, $key) : ''
		)));
	}
	
	public static function hidden_field($name, $value) {
		return CoreHelper::instance()->simple_tag('input', array(
			'type' => 'hidden',
			'name' => $name,
			'value' => $value
		));
	}
	
	public static function check_box($object, $key) {
		$value = self::interpret_pair($object, $key);
		$attributes = array(
			'id' => self::interpret_pair($object, $key, 'id'),
			'name' => self::interpret_pair($object, $key, 'name'),
			'type' => 'checkbox',
			'value' => 'true'
		);

		if(!empty($value)) {
			$attributes['checked'] = true;
		}
			
		$checkbox = CoreHelper::instance()->simple_tag('input', $attributes);
		$hidden = self::hidden_field(self::interpret_pair($object, $key, 'name'), '0');
		
		return $hidden . $checkbox;
	}
	
	public static function select_one($object, $key, $options = array(), $attributes = array()) {
		if(empty($options)) {
			$column = kfa(CoreContext::get($object)->getTable()->getColumns(), $key);
			if(kfa($column, 'type') == 'enum') {
				$options = $column['values'];
			}
		}
		
		$attributes = CoreTagHelper::merge_attributes($attributes, array(
			'id' => self::interpret_pair($object, $key, 'id'),
			'name' => self::interpret_pair($object, $key, 'name'),
		));
		
		if(($selected_value = kfa($attributes, 'value'))) {
			unset($attributes['value']);
		} else {
			$selected_value = self::interpret_pair($object, $key);
		}
		
		$is_true_array = is_true_array($options);
		$option_tags = '';
		foreach($options AS $value => $label) {
			$value = $is_true_array ? $label : $value ;
			$label = $is_true_array ? Inflector::titleize($label) : $label ;
			$option_attributes = array('value' => $value);
			if($value == $selected_value) {
				$option_attributes['selected'] = true;
			}
			
			$option_tags .= CoreHelper::instance()->tag('option', $option_attributes, $label);
		}
		
		return CoreHelper::instance()->tag('select', $attributes, $option_tags);
	}
	
	public static function select_many($object, $key, $options = array(), $attributes = array()) {
		$attributes['multiple'] = true;
		return self::select_one($object, $key, $options, $attributes);
	}
	
	public static function submit_button($value = 'Continue', $options = array()) {
		$options = CoreTagHelper::merge_attributes($options, array(
			'type' => 'submit',
			'value' => $value
		));
		
		return CoreHelper::instance()->simple_tag('input', $options);
	}
	
	public static function reset_button($value = 'Reset', $options = array()) {
		$options = CoreTagHelper::merge_attributes($options, array(
			'type' => 'reset',
			'value' => $value
		));
		
		return CoreHelper::instance()->simple_tag('input', $options);
	}
	
	public static function button_to($value, $url, $options = array()) {
		$method = 'post';
		if(isset($options['method'])) {
			$method = $options['method'];
			unset($options['method']);
		}
		
		$form = self::form_tag($url, $method);
		$form .= CoreHelper::instance()->tag('p', '', self::submit_button($value, $options));
		$form .= self::end_form_tag();
		return $form;
	}
	
	public static function errors_for($object) {
		if(is_string($object)) {
			$object = CoreContext::instance()->{$object};
		}
		
		$stack = $object->errorStack();
		$count = $stack->count();
		$columns = $object->getTable()->getColumns();
		$contents = '';
		
		if($count > 0) {
			$contents = CoreHelper::instance()->tag('h3', null, "{$count} " . Inflector::conditionalPlural($count, 'errors') . ' occurred');
			$contents .= '<ul>';
			foreach($stack AS $field => $errors) {
				$contents .= '<li>' . self::label(String::lowercase(get_class($object)), $field) . '<ul>';
				foreach($errors AS $error) {
					$contents .= CoreHelper::instance()->tag('li', '', self::error_message_for($error, $columns[$field][$error]));
				}
				$contents .= '</ul></li>';
			}
			$contents .= '</ul>';
			
			$contents = CoreHelper::instance()->tag('div', array('class' => 'errors'), $contents);
		}
		
		return $contents;
	}
	
	public static function error_message_for($type, $arguments) {
		if(class_exists('Doctrine_Validator_' . $type)) {
			$function = array(get_class(), $type . '_validation_message');
			$caller = is_array($arguments) ? 'call_user_func_array' : 'call_user_func' ;
			return $caller($function, $arguments);
		}
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
	
	public static function minlength_validation_message($minlength) {
		return "Must be at least {$minlength} characters long";
	}
	
	public static function confirm_validation_message() {
		return 'Did not match confirmation';
	}
	
	public static function notblank_validation_message() {
		return 'Cannot be empty';
	}
	
	public static function unique_validation_message() {
		return 'Must be unique';
	}
}

class CoreFormBuilder {
	private $object_name;
	
	public function __construct($name) {
		$this->object_name = String::lowercase(Inflector::singularize($name));
	}
	
	public function errors() {
		return CoreFormHelper::errors_for($this->object_name);
	}
	
	public function submit() {
		$args = func_get_args();
		return call_user_func_array(array('CoreFormHelper', 'submit_button'), $args);
	}
	
	public function reset() {
		$args = func_get_args();
		return call_user_func_array(array('CoreFormHelper', 'reset_button'), $args);
	}
	
	public function __call($method, $arguments) {
		array_unshift($arguments, $this->object_name);
		return call_user_func_array(array('CoreFormHelper', $method), $arguments);
	}
}