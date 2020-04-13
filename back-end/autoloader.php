<?php
function __autoload($class) {
	$class = strtolower($class);
	$path = 'class/class-';
	require  $path . $class .'.php';
}
?>