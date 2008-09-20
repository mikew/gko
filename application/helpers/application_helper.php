<?php
class ApplicationHelper extends CoreHelper {
	public function write_title() {
		return implode(' - ', $this->locals->title);
	}
}