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
			  cantvotos int NOT NULL,
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

?>