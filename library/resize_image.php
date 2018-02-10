<?php

function resize_image($source_filename, $source_width, $source_height, $source_type, $file_name) {
	if ($source_type == 'jpg')
		$source_type = 'jpeg';

	// Création de l'image source
	switch ($source_type) {
		case 'jpeg':
			$source = imagecreatefromjpeg($source_filename);
			break;
		case 'gif':
			$source = imagecreatefromgif($source_filename);
			break;
		case 'png':
			$source = imagecreatefrompng($source_filename);
			break;
	}

	// Calcul des dimensions puis création de l'image redimensionnée
	if ($source_width == $source_height) {
		$destination_width = 250;
		$destination_height = 250;
	}
	else if ($source_width > $source_height) {
		$destination_width = 250;
		$destination_height = floor(250 * $source_height / $source_width);
	}
	else {
		$destination_height = 250;
		$destination_width = floor(250 * $source_width / $source_height);
	}

	$destination = imagecreatetruecolor($destination_width, $destination_height);

	// Redimensionnement de l'image
	imagecopyresampled($destination, $source, 0, 0, 0, 0, $destination_width, $destination_height, $source_width, $source_height);

	// Enregistrement du fichier
	switch ($source_type) {
		case 'jpeg':
			imagejpeg($destination, 'images/auctions/'.$file_name);
			break;
		case 'gif':
			imagegif($destination, 'images/auctions/'.$file_name);
			break;
		case 'png':
			imagepng($destination, 'images/auctions/'.$file_name);
			break;
	}
}