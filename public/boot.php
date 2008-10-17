<?php
$start = microtime(true);

require_once '../core/initialize.php';

require_once File::join(CORE_HOME, 'init', 'doctrine.php');
require_once File::join(CORE_HOME, 'init', 'routes.php');
require_once File::join(CORE_HOME, 'init', 'controller.php');

$finish = microtime(true);
$duration = $finish - $start;
echo "generated in {$duration} seconds";
?>
