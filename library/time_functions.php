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
	$string_time_left = '<span class="countdown_days">';
	if ($days != 0) {
		$string_time_left .= $days.'j';
	}
	$string_time_left .= '</span>';

	$string_time_left .= '<span class="countdown_hours">';
	if ($hours != 0) {
		if ($string_time_left != '')
			$string_time_left .= ' ';
		$string_time_left .= $hours.'h';
	}
	$string_time_left .= '</span>';

	$string_time_left .= '<span class="countdown_minutes">';
	if ($minutes != 0) {
		if ($string_time_left != '')
			$string_time_left .= ' ';
		$string_time_left .= $minutes.'m';
	}
	$string_time_left .= '</span>';
	
	$string_time_left .= '<span class="countdown_seconds">';
	if ($seconds != 0) {
		if ($string_time_left != '')
			$string_time_left .= ' ';
		$string_time_left .= $seconds.'s';
	}
	$string_time_left .= '</span>';

	return $string_time_left;
}