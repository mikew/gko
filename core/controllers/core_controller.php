<?php
class CoreController {
	private $content = '';
	private $filters = array(
		'before' => array(),
		'after' => array()
	);
	protected $layout = 'application';
	
	protected function application_setup() {}
	protected function controller_setup() {}
	
	final public function run() {
		$this->application_setup();
		$this->controller_setup();
		$this->run_filters('before');
		$this->render();
		$this->run_filters('after');
	}
	
	final public function render() {
		$content = $this->{ACTION}();

		$this->setup_for_mime();
		CoreMime::set_headers();
		CoreHelper::register();
		
		$this->content = empty($content) ? $this->render_file(ACTION) : $content ;

		echo $this->layout === false ? $this->content : $this->render_file(array(APP_HOME, 'views', 'layouts', $this->layout));
	}
	
	final private function setup_for_mime() {
		$callback = 'setup_for_mime_' . CoreMime::current();
		if(method_exists($this, $callback))
			call_user_func(array($this, $callback));
	}
	
	final private function setup_for_mime_rss() {
		$this->layout = false;
	}
	
	final protected function render_file($file, $mime = '') {
		$mime = CoreMime::interpret($mime);
		$file = CoreMime::find_template_file($file, $mime);
		
		$callback = 'render_file_' . $mime;
		$contents = '';
		if(!empty($file)) {
			if(method_exists($this, $callback)) {
				$contents = call_user_func(array($this, $callback), $file);
			} else {
				ob_start();
				include $file;
				$contents = ob_get_contents();
				ob_end_clean();
			}
		}
		
		return $contents;
	}
	
	protected function render_file_rss($file) {
		$feed = new RSSFeed();
		include $file;
		return $feed;
	}
	
	protected function render_file_markdown($file) {
		return Markdown(File::read($file));
	}
	
	final protected function render_partial($name, $data = array(), $locals = array()) {
		$contents = '';
		
		foreach($locals AS $key => $value) {
			$$key = $value;
		}
		
		if(!empty($data)) {
			if(!isset($data[0]))
				$data = array($data);
		} else {
			$data = array($name);
		}

		${$name . '_counter'} = 0;
		foreach($data AS $object) {
			$$name = $object;
			
			$file = CoreMime::find_template_file('_' . $name);
			
			ob_start();
			include $file;
			$contents .= ob_get_contents();
			ob_end_clean();
			
			${$name . '_counter'}++;
		}
		
		return $contents;
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
		$source = is_array($args[0]) ? $args[0] : $args ;
		foreach($source AS $filter) {
			array_push($this->filters[$where], $filter);
		}
	}
	
	final private function run_filters($from) {
		$errors = array();
		
		foreach($this->filters[$from] AS $filter) {
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