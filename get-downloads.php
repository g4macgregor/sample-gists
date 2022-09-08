<?php

/**
 * This snippet was used to create a widget based
 * on the clients need to display either the most recent 
 * downnload or last three (3) product years latest downloads
 * based on a VERSION.DAT in a given {$locaction} position on the site.
 */

// This gets the current product years' most recent download
add_shortcode(
	'latest_download_header',
	function() {
		return get_latest_download( 'header' );
	}
);

// This gets the latest downloads for the last 3 product years
add_shortcode(
	'latest_download_years',
	function() {
		return get_latest_download();
	}
);

function get_latest_download( $location = null ) {
	date_default_timezone_set( 'America/Chicago' );
	$date_parts = date_parse( date( 'Y-m-d' ) );
	$result     = '';

	if ( null === $location ) {
		$current_software_yr = ( $date_parts['month'] >= 2 ? $date_parts['year'] : $date_parts['year'] - 1 );
		$years               = [];

		for ( $i = 0; $i <= 2; $i ++ ) {
			$years[] = $current_software_yr - $i;
		}

		// Build out the last three(3) years based on the date
		foreach ( $years as $year ) {
			// TODO change to pull from shopams and set up redirect t
			$f      = $_SERVER['DOCUMENT_ROOT'] . '/path_to/dat_file/' . $year . '/VERSION.DAT';
			$f_date = date( 'm/d/Y - g:ia', filemtime( $f ) );
			$handle = fopen( $f, 'r' );

			// Get the necessary pieces
			$f_yr = fgetcsv( $handle, 1000, ',' );
			$f_v1 = fgetcsv( $handle, 1000, ',' );
			$f_v2 = fgetcsv( $handle, 1000, ',' );

			$result .= '<div><div style="margin-bottom:10px;"><p><strong>' . $f_yr[0] . '</strong> Latest Version: ' . $f_v1[0] . '.' . $f_v2[0] . '<br/>Uploaded on: ' . $f_date . '</p></div></div>';
		}
	} elseif ( 'header' === $location ) {
		$current_software_yr = ( 3 < $date_parts['month'] ? $date_parts['year'] : $date_parts['year'] - 1 );
		$f                   = $_SERVER['DOCUMENT_ROOT'] . '/path_to/dat_file/' . $current_software_yr . '/VERSION.DAT';
		$handle              = fopen( $f, 'r' );

		// Get the necessary pieces
		$f_yr = fgetcsv( $handle, 1000, ',' );
		$f_v1 = fgetcsv( $handle, 1000, ',' );
		$f_v2 = fgetcsv( $handle, 1000, ',' );

		$result = 'Download Latest Update: ' . $f_v1[0] . '.' . $f_v2[0] . ' ( ' . $current_software_yr . ' )';
	} else {
		$result = 'Error: 101';
	}

	return $result;
}
