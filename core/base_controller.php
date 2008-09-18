<?php
class BaseController {
	private $content = '';
	private $extensions = array(
		'html' => '.phtml'
	);
	
	public function render() {
		$this->content = $this->render_file(ACTION);
		include File::join(FW_HOME, 'views', 'layout.phtml');
	}
	
	protected function render_file($file) {
		$file = substr($file, 0, 1) == '/' ? $file : File::join(FW_HOME, 'views', CONTROLLER, $file);
		$file .= substr($file, strlen($this->extensions['html'])) == $this->extensions['html'] ? '' : $this->extensions['html'];
		
		ob_start();
		include $file;
		$contents = ob_get_contents();
		ob_end_clean();
		
		return $contents;
	}
}