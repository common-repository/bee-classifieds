<?php
 /*
Plugin Name:Bee  Classifieds
Plugin URI:  http://beescripts.com
Description: A responsive classifieds listings plugin that allows to run your own classified listing site with wordpress.
Version:     1.1
Author:      aumsrini
Author URI:  http://beescripts.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: bee-classi
*/
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit; 

if( class_exists('Bee_classi') ) {
        echo "hi";
     }
//Create Pages For Listings
register_activation_hook( __FILE__, 'bee_classi_post_listing_page' );

function bee_classi_post_listing_page(){
    // Create post object
    $add_listing_page = array(
      'post_title'    => 'Post Listing',
      'post_content'  => '[bee-add-listing]',
      'post_status'   => 'publish',
      'post_author'   => get_current_user_id(),
      'post_type'     => 'page',
    );

    // Insert the post into the database
    wp_insert_post( $add_listing_page, '' );
}
register_activation_hook( __FILE__, 'bee_classi_my_listing_page' );

function bee_classi_my_listing_page(){
    // Create post object
    $my_listing_page = array(
      'post_title'    => 'My Listings',
      'post_content'  => '[bee-my-listings][bee-edit-listing]',
      'post_status'   => 'publish',
      'post_author'   => get_current_user_id(),
      'post_type'     => 'page',
    );

    // Insert the post into the database
    wp_insert_post( $my_listing_page, '' );
}
register_activation_hook( __FILE__, 'bee_classi_view_listing_page' );

function bee_classi_view_listing_page(){
    // Create post object
    $view_listing_page = array(
      'post_title'    => 'View Listings',
      'post_content'  => '[bee-view-listing]',
      'post_status'   => 'publish',
      'post_author'   => get_current_user_id(),
      'post_type'     => 'page',
    );

    // Insert the post into the database
    wp_insert_post( $view_listing_page, '' );
}
//END Pages For Listings

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bee-classi-activator.php
 */
function activate_bee_classi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bee-classi-activator.php';
	Bee_classi_Activator::activate();
	
}


	
function beeclassi_styles()
	{ 
	
	wp_enqueue_style( 'bee-classi', plugin_dir_url( __FILE__ ) . 'public/css/beeclassi.css');
	wp_enqueue_style('bootstrap', plugin_dir_url( __FILE__ ) . 'public/css/bootstrap.css' );
	wp_enqueue_style('fontawesome', plugin_dir_url( __FILE__ ) . 'public/font-awesome/css/font-awesome.min.css');
	wp_enqueue_style('thumbscroller', plugin_dir_url( __FILE__ ) . 'public/css/jquery.mCustomScrollbar.css');
}


add_action( 'wp_enqueue_scripts', 'beeclassi_styles' );

function beeclassi_scripts()
	{ 
	wp_enqueue_script( 'bee-classi', plugin_dir_url( __FILE__ ) . 'public/js/beeclassi.js', array( 'jquery' ));
		wp_enqueue_script( 'classimin', plugin_dir_url( __FILE__ ) . 'public/js/beeclassi.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'easing', plugin_dir_url( __FILE__ ) . 'public/js/easing.1.3.js', array( 'jquery' ) );
		wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'public/js/bootstrap.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'matchheight', plugin_dir_url( __FILE__ ) . 'public/js/match_height.js', array( 'jquery' ));
		wp_enqueue_script( 'thumbscroller', plugin_dir_url( __FILE__ ) . 'public/js/jquery.mCustomScrollbar.concat.min.js', array( 'jquery' ) );
    }
		
		add_action( 'wp_enqueue_scripts', 'beeclassi_scripts' );
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bee-classi-deactivator.php
 */
 
  function bee_classi_options_admin_style() {
        wp_register_style( 'bee_classi_admin_css', plugin_dir_url( __FILE__ ) . 'public/css/beeclassi-admin.css', false, '1.0.0' );
        wp_enqueue_style( 'bee_classi_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'bee_classi_options_admin_style' );
function deactivate_bee_classi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bee-classi-deactivator.php';
	Bee_classi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bee_classi' );
register_deactivation_hook( __FILE__, 'deactivate_bee_classi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bee-classi.php';
require plugin_dir_path( __FILE__ ) . 'bee-classi-listing.php'; // Path to the plugin's main file

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
