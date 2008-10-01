<?php
class File {
	public function join() {
		$args = func_get_args();
		return Core::join_paths($args);
	}
}