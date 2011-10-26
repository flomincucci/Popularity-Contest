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
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql1);
		update_option("poptest_db_version", $poptest_db_version);	 	
	 }
	 
}
	 
register_activation_hook(__FILE__,'instalacionpoptest');

function getvotes($user) {
	// Retrieves the votes for a user (voted)
	global $wpdb;
		$votos = $wpdb->get_var( "SELECT count(1) FROM ".$wpdb->prefix . "poptest_votos where usuario = ".$user." group by usuario");
		var_dump($votos);
		if (!$votos) return 0;
		return $votos;
}

function getvotesavailable($user) {
	// Retrieves the votes available for a user (voter)
	global $wpdb;
		$votos = $wpdb->get_var( "SELECT count(1) FROM ".$wpdb->prefix . "poptest_votos where votante = ".$user." group by votante");
		if ($votos == 1) return 0;
		if ($votos == false) return 1;
		else return false;
}

function vote($voter, $uservoted) {
	global $wpdb;
	if(is_user_logged_in()) {
		$test = $wpdb->get_var("SELECT usuario FROM ".$wpdb->prefix . "poptest_votos where votante = ".$voter." and usuario = ".$uservoted);
		if( ($test == $uservoted) || (getvotesavailable($voter) == 0) ) {
			return false;
		} else {
			$wpdb->insert($wpdb->prefix . "poptest_votos", array('usuario' => $uservoted, 'votante' => $voter));
			return true;
		}
	} else {
		//Error
		return false;
	}
}

?>
