<?php
$map->connect('home', '/', array('controller'=>'welcome', 'action'=>'index'));
$map->connect('/news/archive/:key', array(
	'controller' => 'news',
	'action' => 'archive'
));
$map->connect(':controller/:action/:id');