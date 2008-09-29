<?php
class SQLiteAdapter extends AbstractAdapter {
	private $connection;
	public $connected = false;
	
	public function __construct($params) {
		if(($this->connection = new SQLiteDatabase($params['database'])) !== false)
			$this->connected = true;
	}
}