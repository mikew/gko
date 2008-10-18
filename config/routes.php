<?php
$map->connect('root', '/', array('controller'=>'welcome', 'action'=>'index'));
$map->connect('/news/archive/:key', array(
	'controller' => 'news',
	'action' => 'show'
));
$map->connect(':controller/:action/:id');
