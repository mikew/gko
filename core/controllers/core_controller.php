<?php
class CoreController {
	private $content = '';
	private $helpers = array();
	private $filters = array(
		'before' => array(),
		'after' => array()
	);
	private $core;
	protected $layout = 'application';
	
	protected function _setup() {}
	
	public function run() {
		$this->core = new Core();
		
		$this->_setup();
		$this->run_filters('before');
		$this->core->set_headers();
		$this->render();
		$this->run_filters('after');
	}
	
	protected function set_status($status) {
		$this->core->status = $status;
	}
	
	public function render() {
		$content = $this->{ACTION}();
		
		$this->content = empty($content) ? $this->render_file(ACTION) : $content ;
		
		// I should clean this up to allow for actions to disable the layout
		// and automatically check for layout.phtnl, and check for a layout variable
		echo $this->layout === false ? $this->content : $this->render_file(array(APP_HOME, 'views', 'layouts', $this->layout));
	}
	
	protected function render_file($file, $extension = '') {
		$helpers = $this->register_helper(CONTROLLER);
		$file = $this->core->find_template_file($file, $extension);
		
		ob_start();
		include $file;
		$contents = ob_get_contents();
		ob_end_clean();
		
		return $contents;
	}
	
	protected function render_partial($name, $data = array(), $locals = array()) {
		$contents = '';
		$helpers = $this->register_helper(CONTROLLER);
		
		foreach($locals AS $key => $value) {
			$$key = $value;
		}
		if(empty($data))
			$data = array($name);
			
		${$name . '_counter'} = 0;
		foreach($data AS $object) {
			$$name = $object;
			
			$file = $this->core->find_template_file('_' . $name);
			
			ob_start();
			include $file;
			$contents .= ob_get_contents();
			ob_end_clean();
			
			${$name . '_counter'}++;
		}
		
		return $contents;
	}
	
	protected function add_before_filter() {
		$args = func_get_args();
		$this->add_filter('before', $args);
	}
	protected function add_after_filter() {
		$args = func_get_args();
		$this->add_filter('after', $args);
	}
	private function add_filter($where, $args) {
		$source = is_array($args[0]) ? $args[0] : $args ;
		foreach($source AS $filter) {
			array_push($this->filters[$where], $filter);
		}
	}
	
	private function run_filters($from) {
		$errors = array();
		
		foreach($this->filters[$from] AS $filter) {
			$result = call_user_func(array($this, $filter));
			if($result === false)
				array_push($errors, $filter);
		}
		
		if(!empty($errors))
			die('errors in ' . $from . ' filters: ' . implode(', ', $errors));
	}
	
	private function register_helper($helper) {
		$helper = strtolower($helper);
		$klass = $helper . 'Helper';
		if(!isset($this->helpers[$helper]))
			$this->helpers[$helper] = new $klass();
		
		return $this->helpers[$helper];
	}
}