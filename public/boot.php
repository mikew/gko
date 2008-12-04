<?php
session_start();
$start = microtime(true);

require_once '../core/initialize.php';

require_once File::join(CORE_HOME, 'init', 'routes.php');
require_once File::join(CORE_HOME, 'init', 'controller.php');

$finish = microtime(true);
$duration = $finish - $start;

echo '<!-- ' .
		round($duration, 5) .
		's (' .
		round(1 / $duration, 2) .
		' requests per second)' .
		' -->';
?>
