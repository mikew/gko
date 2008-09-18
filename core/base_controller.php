<?php
class BaseController {
	private $content = '';
	private $extensions = array();
	private $mimes = array();
	private $mime = 'html';
	
	protected $status = 200;
	
	public function render() {
		$this->add_mime('html', '.phtml', 'application/xhtml+xml');
		
		$content = $this->{ACTION}();
		$this->content = empty($content) ? $this->render_file(ACTION) : $content ;
		
		header("HTTP/1.0 {$this->status}");
		header("Content-Type: {$this->mimes[$this->mime]}");
		include File::join(FW_HOME, 'views', 'layout.phtml');
	}
	
	protected function add_mime($shorthand, $extension, $content_type) {
		$this->extensions[$shorthand] = $extension;
		$this->mimes[$shorthand] = $content_type;
	}
	
	protected function render_file($file, $extension = '') {
		$extension = empty($extension) ? $this->extensions[$this->mime] : $this->extensions[$extension];
		$file = substr($file, 0, 1) == '/' ? $file : File::join(FW_HOME, 'views', CONTROLLER, $file);
		$file .= substr($file, $extension) == $extension ? '' : $extension;
		
		ob_start();
		include $file;
		$contents = ob_get_contents();
		ob_end_clean();
		
		return $contents;
	}
}