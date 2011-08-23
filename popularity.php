<?php
/*
Plugin Name: Popularity Contest
Plugin URI: http://florenciamincucci.com.ar
Description: Users can vote each other
Version: 1.0
Author: Florencia Mincucci
*/

/* Instalacion */

function instalacionpoptest() {
   global $wpdb;

   $table_name1 = $wpdb->prefix . "poptest_votos";
	 $table_name2 = $wpdb->prefix . "poptest_votosdisponibles";
	 $poptest_db_version = "1.0"; 
	 if($wpdb->get_var("SHOW TABLES LIKE '$table_name1'") == $table_name1) {
	 	add_option("poptest_db_version", $poptest_db_version);	
	 }
	 $installed_ver = get_option( "poptest_db_version" );

   if($installed_ver != $poptest_db_version) {
		$sql1 = "CREATE TABLE " . $table_name1 . " (
			  id int NOT NULL AUTO_INCREMENT,
			  usuario int NOT NULL,
			  votante int NOT NULL,
			  UNIQUE KEY id (id)
			);";
		$sql2 = "CREATE TABLE " . $table_name2 . " (
			  id int NOT NULL AUTO_INCREMENT,
			  usuario int NOT NULL,
			  cantvotos int NOT NULL,
			  UNIQUE KEY id (id)
			);";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql1);
		dbDelta($sql2);
		update_option("poptest_db_version", $poptest_db_version);	 	
	 }
	 
}
	 
register_activation_hook(__FILE__,'instalacionpoptest');

function getvotes($user) {
	// Retrieves the votes for a user (voted)
	global $wpdb;
	if(user_can($voter, 'banda')) {
		$votos = $wpdb->get_var( "SELECT count(1) FROM ".$wpdb->prefix . "poptest_votos where usuario = ".$user." group by usuario");
		return $votos;
	} else {
		//Error
	}
}

function getvotesavailable($user) {
	// Retrieves the votes available for a user (voter)
	global $wpdb;
	if(user_can($voter, 'integrante')) {
		$votos = $wpdb->get_var( "SELECT cantvotos FROM ".$wpdb->prefix . "poptest_votosdisponibles where usuario = ".$user);
		return $votos;
	}
}

function getvotados($user) {
	// Retrieves the bands voted by a user
	global $wpdb;
	if(user_can($voter, 'integrante')) {
		$votos = $wpdb->get_var( "SELECT count(1) FROM ".$wpdb->prefix . "poptest_votos where votante = ".$user." group by votante");
		return $votos;
	}
}

function vote($voter, $uservoted) {
 // Action of voting. One logged-in user votes a band. The user loses one credit, the band wins a vote. Must check the type of user (bands can't vote, only band can be voted)
	if(is_user_logged_in() && user_can($voter, 'integrante') && user_van($uservoted, 'banda') {
		$wpdb->insert($wpdb->prefix . "poptest_votos", array('usuario' => $uservoted, 'votante' => $voter));
		losecredit($voter,1);
	} else {
		//Error
	}
}

function losecredit(int $user, int $quantity) {
	// The user loses $quantity vote credits. Returns the amount of credits available. User must be 'integrante'
	global $wpdb;
	if(user_can($voter, 'integrante')) {
		if($wpdb->get_var("SELECT usuario FROM ".$wpdb->prefix . "poptest_votosdisponibles where usuario = ".$user == $user) {
			// User already exists in the table
			$votosactuales = getvotesavailable($user);
			$itsok = $wpdb->update($wpdb->prefix . "poptest_votosdisponibles", array('cantvotos' => $votosactuales - $quantity), array('usuario' => $user));
			if ($itsok)	$votosdisponibles = $votosactuales - $quantity;
			else $votosdisponibles = $votosactuales;
		} else {
			// Insert user in the table
			$votosdisponibles = 10 - $quantity;
			$wpdb->insert($wpdb->prefix . "poptest_votosdisponibles", array('usuario' => $user, 'cantvotos' => $votosdisponible));
		}
		return $votosdisponibles;
	} else {
		//Error
	}
}

?>