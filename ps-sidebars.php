<?php
/**
 * Plugin Name: Benutzerdefinierte Seitenleisten
 * Plugin URI:  https://n3rds.work/piestingtal-source-project/ps-seitenleisten/
 * Description: Ermöglicht das Erstellen von Widget-Bereichen und benutzerdefinierten Seitenleisten. Ersetze ganze Seitenleisten oder einzelne Widgets für bestimmte Beiträge und Seiten.
 * Version:     3.5.6
 * Author:      WMS N@W
 * Author URI:  https://n3rds.work
 * Textdomain:  ps-sidebars
 * 
 */

/*
Copyright DerN3rd (https://n3rds.work)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
This plugin was originally developed by Javier Marquez.
http://arqex.com/
*/
require 'inc/external/psource-plugin-update/plugin-update-checker.php';
$MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://n3rds.work//wp-update-server/?action=get_metadata&slug=benutzerdefinierte-seitenleisten', 
	__FILE__, 
	'benutzerdefinierte-seitenleisten' 
);

function inc_sidebars_init() {
	if ( class_exists( 'BenutzerdefinierteSeitenleisten' ) ) {
		return;
	}

	/**
	 * Do not load plugin when saving file in WP Editor
	 */
	if ( isset( $_REQUEST['action'] ) && 'edit-theme-plugin-file' == $_REQUEST['action'] ) {
		return;
	}

	/**
	 * if admin, load only on proper pages
	 */
	if ( is_admin() && isset( $_SERVER['SCRIPT_FILENAME'] ) ) {
		$file = basename( $_SERVER['SCRIPT_FILENAME'] );
		$allowed = array(
			'edit.php',
			'admin-ajax.php',
			'post.php',
			'post-new.php',
			'widgets.php',
		);
		/**
		 * Allowed pages array.
		 *
		 * To change where Custom Sidebars is loaded, use this filter.
		 *
		 * @since 3.2.3
		 *
		 * @param array $allowed Allowed pages list.
		 */
		$allowed = apply_filters( 'benutzerdefinierte_seitenleisten_allowed_pages_array', $allowed );
		if ( ! in_array( $file, $allowed ) ) {
			return;
		}
	}

	$plugin_dir = dirname( __FILE__ );
	$plugin_dir_rel = dirname( plugin_basename( __FILE__ ) );
	$plugin_url = plugin_dir_url( __FILE__ );

	define( 'CSB_PLUGIN', __FILE__ );
	define( 'CSB_IS_PRO', false );
	define( 'CSB_VIEWS_DIR', $plugin_dir . '/views/' );
	define( 'CSB_INC_DIR', $plugin_dir . '/inc/' );
	define( 'CSB_JS_URL', $plugin_url . 'assets/js/' );
	define( 'CSB_CSS_URL', $plugin_url . 'assets/css/' );
	define( 'CSB_IMG_URL', $plugin_url . 'assets/img/' );

	// Include function library.
	$modules[] = CSB_INC_DIR . 'external/wpmu-lib/core.php';
	$modules[] = CSB_INC_DIR . 'class-ps-sidebars.php';
	
	// Free-version configuration - no drip campaign yet...
	$cta_label = false;
	$drip_param = false;
	

	

	foreach ( $modules as $path ) {
		if ( file_exists( $path ) ) { require_once $path; }
	}


	// Initialize the plugin
	BenutzerdefinierteSeitenleisten::instance();
}

inc_sidebars_init();

if ( ! class_exists( 'BenutzerdefinierteSeitenleistenEmptyPlugin' ) ) {
	class BenutzerdefinierteSeitenleistenEmptyPlugin extends WP_Widget {
		public function __construct() {
			parent::__construct( false, $name = 'BenutzerdefinierteSeitenleistenEmptyPlugin' );
		}
		public function form( $instance ) {
			//Nothing, just a dummy plugin to display nothing
		}
		public function update( $new_instance, $old_instance ) {
			//Nothing, just a dummy plugin to display nothing
		}
		public function widget( $args, $instance ) {
			echo '';
		}
	} //end class
} //end if class exists


// Translation.
function inc_sidebars_init_translation() {
	load_plugin_textdomain( 'ps-sidebars', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'inc_sidebars_init_translation' );
