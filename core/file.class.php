<?php
class File {
	public function join() {
		$args = func_get_args();
		$args = is_array($args[0]) ? $args[0] : $args;
		return implode('/', $args);
	}
}