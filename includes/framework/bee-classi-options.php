<?php
/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class Myprefix_Admin {

	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'bee_classi_options';

	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'bee_classi_options';

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Holds an instance of the object
	 *
	 * @var Myprefix_Admin
	 **/
	private static $instance = null;

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	private function __construct() {
		// Set our title
		$this->title = __( 'Beeclassifieds Options', 'myprefix' );
	}

	/**
	 * Returns the running object
	 *
	 * @return Myprefix_Admin
	 **/
	public static function get_instance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>	<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div></td>
    <td><table  width="26%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="pro_table_header" height="37" ><h3 align="center" >UPGRADE PRO VERSION </h3></td>
  </tr>
  <tr>
    <td  class="pro_table_content"><ul><li>Create Unlimited Listing Plans</li>
      <li>View User Subscribed Plans</li>
      <li>Fully Responsive</li>
      <li>Number of listings to show in listings page</li>
      <li>Show /Hide price option</li>
      <li>Define your currency symbol</li>
      <li>Disable /Enable listing thumbnail links</li>
      <li>Paypal payment gateway integrated with sandbox mode for testing.</li>
      <li>Ad Duration</li>
      <li>Limit Number of ads&nbsp;in each plan</li>
      <li>Auto trash&nbsp;ads after duration expires</li>
      <li>Featured Ads Scrolling</li>
    </ul>
    </td>
  </tr>
  <tr>
    <td><div align="center"><a href="https://beeplguins.com/product/bee-classifieds-pro"><img src="<?php echo plugin_dir_url( __FILE__ ).'../buy-now-credit-card-icons-button.png' ?>" width="339" height="160" /></a></div></td>
  </tr>
</table></td>
  </tr>
</table>

	
		<?php
	}

	function add_options_page_metabox() {

		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		// Set our CMB2 fields

		$cmb->add_field( array(
			'name' => __( 'No.of listings to be show per page', 'bee_classi_page' ),
			'desc' => __( 'Enter number of listings to show per page', 'bee_classi_page' ),
			'id'   => 'no_of_listing',
			'type' => 'text_small',
			'default' => '6','attributes' => array(
		'type' => 'number',
		'pattern' => '\d*',
	),
		) );

		$cmb->add_field( array(
			'name'    => __( 'Hide Price', 'bee_classi_hide_price' ),
			'desc'    => __( 'If you need run listings without price like directory then check Yes to disable price', 'bee_classi_options' ),
			'id'      => 'hide_price',
			   'type'    => 'radio_inline',
			
    
    'options'          => array(
        'yes' => __( 'Yes', 'bee_classi_hide_price' ),
        'no'     => __( 'No', 'bee_classi_hide_price' ),
        
    ),
	'default' => 'no',
		) );
		
		
		$cmb->add_field( array(
			'name' => __( 'Enter Your Currency Symbol', 'bee_classi_currency' ),
			'desc' => __( 'Enter your prefered currency symbol for price', 'bee_classi_page' ),
			'id'   => 'bee_currency_symbol',
			'type' => 'text_small',
			
		) );
		
$cmb->add_field( array(
			'name'    => __( 'Thumbnail Link', 'bee_classi_enable_link' ),
			'desc'    => __( 'If you need make listing detail link in thubnail  then check Yes to enable link', 'bee_classi_options' ),
			'id'      => 'enable_link',
			   'type'    => 'radio_inline',
			
    
    'options'          => array(
        'yes' => __( 'Yes', 'bee_classi_enable_link' ),
        'no'     => __( 'No', 'bee_classi_enable_link' ),
        
    ),
	'default' => 'yes',
		) );
		
		
		
	}
	
	

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'myprefix' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

}

/**
 * Helper function to get/return the Myprefix_Admin object
 * @since  0.1.0
 * @return Myprefix_Admin object
 */
function myprefix_admin() {
	return Myprefix_Admin::get_instance();
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function myprefix_get_option( $key = '' ) {
	return cmb2_get_option( myprefix_admin()->key, $key );
}

// Get it started
myprefix_admin();
