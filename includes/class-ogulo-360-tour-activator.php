<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.ogulo.de/
 * @since      1.0.0
 *
 * @package    Ogulo_360_Tour
 * @subpackage Ogulo_360_Tour/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ogulo_360_Tour
 * @subpackage Ogulo_360_Tour/includes
 * @author     Rextheme <support@rextheme.com>
 */
class Ogulo_360_Tour_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		wp_safe_redirect( 'admin.php?page=ogulo-360-tour');
	}

}
