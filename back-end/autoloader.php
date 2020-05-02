<?php
function classAutoloader($class) {
	$class = strtolower($class);
	$path = 'class/class-';
	require  $path . $class .'.php';
}
spl_autoload_register('classAutoloader');
?>