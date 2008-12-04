<?php
class CoreController {
	public $flash;
	private $content = '';
	private static $_before_filters = array();
	private static $_after_filters = array();
	protected $before_filters = array();
	protected $after_filters = array();
	protected $action_to_render;
	protected $layout = 'application';
	
	final public function run() {
		Core::cascade_method($this, 'setup', array($this));
		$this->flash = new CoreFlash();
		$this->run_filters('before');
		$this->render();
		$this->flash->reset();
		$this->run_filters('after');
	}
	
	final public function render() {
		$this->action_to_render = ACTION;
		$content = $this->{ACTION}();

		$this->setup_for_mime();
		CoreMime::set_headers();
		
		$this->content = empty($content) ? CoreView::render($this->action_to_render) : $content ;

		echo $this->layout === false ? $this->content : CoreView::render("layouts/{$this->layout}");
	}
	
	final public function content_for_layout() {
		return $this->content;
	}
	
	final private function setup_for_mime() {
		$callback = 'setup_for_mime_' . CoreMime::current();
		if(method_exists($this, $callback))
			call_user_func(array($this, $callback));
	}
	
	private function setup_for_mime_rss() {
		$this->layout = false;
	}
	
	final protected function add_before_filter() {
		$args = func_get_args();
		$this->add_filter('before', $args);
	}
	final protected function add_after_filter() {
		$args = func_get_args();
		$this->add_filter('after', $args);
	}
	final private function add_filter($where, $args) {
		$source = Core::interpret_options($args);
		foreach($source AS $filter) {
			array_push($this->{$where . '_filters'}, $filter);
		}
	}
	
	final private function run_filters($from) {
		$errors = array();
		
		$array = $this->{$from . '_filters'};
		foreach($this->{$from . '_filters'} AS $filter) {
			$result = call_user_func(array($this, $filter));
			if($result === false)
				array_push($errors, $filter);
		}
		
		if(!empty($errors))
			die('errors in ' . $from . ' filters: ' . implode(', ', $errors));
	}
	
	final private function request_auth($zone) {
		CoreMime::set_status(401);
		header('WWW-Authenticate: Basic realm="' . $zone . '"');
	}
	
	public function process_http_auth($username, $password) {
		$auth = array();
		if(!empty($username) && !empty($password))
			array_push($auth, $username, $password);

		return !empty($username) && !empty($password) ? array($username, $password) : false;
	}
	
	final protected function http_auth($callback = '', $zone = 'Restricted') {
		$result = array();
		
		if(isset($_SERVER['PHP_AUTH_USER'])) {
			$u = $_SERVER['PHP_AUTH_USER'];
			$p = $_SERVER['PHP_AUTH_PW'];
			
			if(empty($callback))
				$callback = array($this, 'process_http_auth');
			
			$result = call_user_func($callback, $u, $p);
		}
		
		if(!empty($result))
			return $result;
		else
			$this->request_auth($zone);
	}
}
