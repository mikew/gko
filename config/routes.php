<?php
$map->resource('admin/game', 'admin/games');
$map->resource('admin/post', 'admin/posts');
$map->resource('admin/author', 'admin/authors');

$map->connect('root', '/', array('controller'=>'welcome', 'action'=>'index'));

$map->connect('/news/archive/:key', array(
	'controller' => 'posts',
	'action' => 'show'
));
$map->connect('/news', array(
	'controller' => 'posts',
	'action' => 'index'
));

$map->connect('rss', '/rss', array('controller' => 'posts', 'action' => 'index', 'format' => 'rss'));
$map->connect(':controller/:action/:id.:format', array('format' => 'html'));
