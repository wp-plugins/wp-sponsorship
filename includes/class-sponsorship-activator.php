<?php

/**
 * Fired during plugin activation
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Sponsorship
 * @subpackage Sponsorship/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sponsorship
 * @subpackage Sponsorship/includes
 * @author     Thomas Karatzas <thomas.karatzas@mail.mcgill.ca>
 */
class Sponsorship_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		/*global $wpdb;

		$table_name = $wpdb->prefix.'email_sender';

		$table_name_2 = $wpdb->prefix. 'email_contacts';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name(
		  ID INT NOT NULL,
		  name TEXT NOT NULL ,
		  email TEXT NOT NULL ,
		  time DATETIME NOT NULL
		 ) $charset_collate;";

		$sql_2 = "CREATE TABLE $table_name_2(
		  ID INT NOT NULL AUTO_INCREMENT ,
		  parent_ID INT NOT NULL ,
		  email TEXT NOT NULL ,
		  name TEXT NOT NULL  ,
			time_sent DATETIME NOT NULL,
		 PRIMARY KEY (ID)
		 ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		dbDelta( $sql_2 );*/



	}

}
