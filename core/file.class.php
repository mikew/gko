<?php
class File {
	public function join() {
		$args = func_get_args();
		return implode('/', $args);
	}
}