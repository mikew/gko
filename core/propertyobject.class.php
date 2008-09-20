<?php
class PropertyObject {
	public function __construct($properties) {
		foreach($properties AS $key => $value) {
			$this->{$key} = $value;
		}
	}
}