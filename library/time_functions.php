<?php

// Renvoie le temps restant sous forme d'une chaîne
function get_time_left($end_time) {
	// Calcul de la valeur en timestamp
	$time_left = $end_time - time();
	// Calcul de la valeur en format humain
	$seconds = $time_left % 60;
	$time_left = floor($time_left / 60);
	$minutes = $time_left % 60;
	$time_left = floor($time_left / 60);
	$hours = $time_left % 24;
	$days = floor($time_left / 24);

	// Écriture de la valeur
	$string_time_left = '';
	if ($days != 0) {
		$string_time_left .= $days.'j';
	}

	if ($hours != 0) {
		if ($string_time_left != '')
			$string_time_left .= ' ';
		$string_time_left .= $hours.'h';
	}

	if ($minutes != 0) {
		if ($string_time_left != '')
			$string_time_left .= ' ';
		$string_time_left .= $minutes.'m';
	}
	
	if ($seconds != 0) {
		if ($string_time_left != '')
			$string_time_left .= ' ';
		$string_time_left .= $seconds.'s';
	}

	return $string_time_left;
}