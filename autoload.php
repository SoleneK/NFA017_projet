<?php

function autoloader ($classname) {
	require $classname . '.php';
}

spl_autoload_register ('autoloader');