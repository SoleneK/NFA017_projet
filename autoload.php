<?php

function autoloader ($classname) {
	require 'classes/' . $classname . '.php';
}

spl_autoload_register ('autoloader');