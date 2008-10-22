<?php
$map->resource('admin/game', 'admin/games');
$map->resource('admin/post', 'admin/posts');
$map->connect('root', '/', array('controller'=>'welcome', 'action'=>'index'));
$map->connect('/news/archive/:key', array(
	'controller' => 'news',
	'action' => 'show'
));
$map->connect('rss', '/rss', array('controller' => 'news', 'action' => 'index', 'format' => 'rss'));
$map->connect(':controller/:action/:id.:format', array('format' => 'html'));
