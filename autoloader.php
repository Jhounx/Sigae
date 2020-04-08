<?php
function autoload($class) {
	$class = strtolower($class);
 	$path = 'back-end/';
 	require_once  $path . $class .'.php';
}
spl_autoload_register("autoload")
?>