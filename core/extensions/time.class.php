<?php
class Time {
	static public function minutes($i) {
		return 60 * $i;
	}
	static public function minute() { return self::minutes(1); }
	
	static public function hours($i) {
		return self::minutes(60 * $i);
	}
	static public function hour() { return self::hours(1); }
	
	static public function days($i) {
		return self::hours(24 * $i);
	}
	static public function day() { return self::days(1); }
	
	static public function weeks($i) {
		return self::days(7 * $i);
	}
	static public function week() { return self::weeks(1); }
	
	static public function now() {
		return time();
	}
}