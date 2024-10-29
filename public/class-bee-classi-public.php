<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://beescripts.com
 * @since      1.0.0
 *
 * @package    Bee_classi
 * @subpackage Bee_classi/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bee_classi
 * @subpackage Bee_classi/public
 * @author     aumsrini <seenu.ceo@gmail.com>
 */
class Bee_classi_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bee_classi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bee_classi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/beeclassi.css', array(), $this->version, 'all' );
		wp_enqueue_style('bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );
		wp_enqueue_style('fontawesome', plugin_dir_url( __FILE__ ) . 'font-awesome/css/font-awesome.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
	

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bee_classi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bee_classi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/beeclassi.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'classimin', plugin_dir_url( __FILE__ ) . 'js/beeclassi.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'easing', plugin_dir_url( __FILE__ ) . 'js/easing.1.3.js', array( 'jquery' ), $this->version, false );
	

	}

}
