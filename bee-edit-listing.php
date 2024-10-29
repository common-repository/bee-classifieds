<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
//checking for edit request
if(isset($_REQUEST['i']))  {

$_SESSION['beeid']=$_REQUEST['i'];

//generate edit form

 function bee_edit_frontend_form_register() {
	$cmb = new_cmb2_box( array(
		'id'           => 'front-end-post-form',
		'object_types' => array( 'beeclassifieds' ),
		'hookup'       => false,
		'save_fields'  => false,
		
	) );
	$cmb->add_field( array(
		'name'    => __( 'New Listing Title', 'bee-post-submit' ),
		'id'      => 'bee_listing_title',
		'type'    => 'text',
		'default' => get_the_title($_SESSION['beeid']),
		 'attributes'  => array(
        'required'    => 'required',
    ),
	) );
	
	$cmb->add_field( array(
    'name'     => __( 'Listing Category', 'bee-post-submit' ),
    'desc'     => 'Selct your listing category',
	'show_option_none' => false,
    'default'          => 'custom',
    'id'       => 'bee_listing_category',
    'type'    => 'select',
     'options'          => bee_get_term_options(),
   
) );


	$cmb->add_field( array(
		'name'    => __( 'Listing Description', 'bee-post-submit' ),
		'id'      => 'bee_listing_description',
		'type'    => 'textarea_small',
		 'attributes'  => array(
        'required'    => 'required',
    ),
		
	) );
	
	$cmb->add_field( array(
		'name' => __( 'Price', 'bee-post-submit' ),
		'desc' => __( 'If your listing item for sale then enter your price', 'bee-post-submit' ),
		'id'   => 'bee_listing_price',
		'type' => 'text',
	) );
	
	$cmb->add_field( array(
		'name' => __( 'Upload Images', 'cmb2' ),
		'desc' => __( 'Upload your listing images', 'bee-post-submit' ),
		'id'   => 'bee_listing_images',
		'type' => 'file_list',
	) );
	
		$cmb->add_field( array(
		'name' => __( 'Contact Address', 'cmb2' ),
		'desc' => __( 'Enter your contact address', 'bee-post-submit' ),
		'id'   => 'bee_listing_address',
		'type' => 'textarea_small',
		 'attributes'  => array(
        'required'    => 'required',
    ),
	) );
	
		$cmb->add_field( array(
		'name' => __( 'Website Url', 'cmb2' ),
		'desc' => __( 'Enter your website url', 'bee-post-submit' ),
		'id'   => 'bee_listing_url',
		'type' => 'text_url',
	) );
	$cmb->add_field( array(
		'name' => __( 'Your Email', 'cmb2' ),
		'desc' => __( 'Please enter your email so we can contact you if we use your post.', 'bee-post-submit' ),
		'id'   => 'bee_listing_email',
		'type' => 'text_email',
		 'attributes'  => array(
        'required'    => 'required',
    ),
	) );
}
add_action( 'cmb2_init', 'bee_edit_frontend_form_register' );
/**
 * Gets the front-end-post-form cmb instance
 *
 * @return CMB2 object
 */
function bee_edit_frontend_cmb2_get() {
	// Use ID of metabox in bee_frontend_form_register
	$metabox_id = 'front-end-post-form';
	// Post/object ID is not applicable since we're using this form for submission
	$object_id  = $_SESSION['beeid'];
	// Get CMB2 metabox object
	return cmb2_get_metabox( $metabox_id, $object_id );
}
/**
 * Handle the cmb-frontend-form shortcode
 *
 * @param  array  $atts Array of shortcode attributes
 * @return string       Form html
 */
function bee_edit_do_frontend_form_submission_shortcode( $atts = array() ) {
	// Get CMB2 metabox object
	$cmb = bee_edit_frontend_cmb2_get();
	// Get $cmb object_types
	$post_types = $cmb->prop( 'object_types' );
	// Current user
	$user_id = get_current_user_id();
	// Parse attributes
	$atts = shortcode_atts( array(
		'post_author' => $user_id ? $user_id : 1, // Current user, or admin
		'post_status' => 'publish',
		'post_type'   => reset( $post_types ), // Only use first object_type in array
	), $atts, 'cmb-frontend-form' );
	/*
	 * Let's add these attributes as hidden fields to our cmb form
	 * so that they will be passed through to our form submission
	 */
	foreach ( $atts as $key => $value ) {
		$cmb->add_hidden_field( array(
			'field_args'  => array(
				'id'    => "atts[$key]",
				'type'  => 'hidden',
				'default' => $value,
			),
		) );
	}
	// Initiate our output variable
	$output = '';
	// Get any submission errors
	if ( ( $error = $cmb->prop( 'submission_error' ) ) && is_wp_error( $error ) ) {
		// If there was an error with the submission, add it to our ouput.
		$output .= '<h3 class="bee-error-msg">' . sprintf( __( 'There was an error in the submission: %s', 'bee-post-submit' ), '<strong>'. $error->get_error_message() .'</strong>' ) . '</h3>';
	}
	// If the post was submitted successfully, notify the user.
	if ( isset( $_GET['post_submitted'] ) && ( $post = get_post( absint( $_GET['post_submitted'] ) ) ) ) {
		// Get submitter's name
		
		
		$name = get_post_meta( $post->ID, 'submitted_author_name', 1 );
		$name = $name ? ' '. $name : '';
		// Add notice of submission to our output
		$output .= '<h3 class="bee-success-msg">' . sprintf( __( 'Thank you%s, your listing updated sucessfully.', 'bee-post-submit' ), esc_html( $name ) ) . '</h3>';
	}
	// Get our form
	$object_id="fake-object-id";
	$output .= cmb2_get_metabox_form( $cmb,$object_id, array( 'save_button' => __( 'Update Listing', 'bee-post-submit' ) ) );
	return $output;
}


add_shortcode( 'bee-edit-listing', 'bee_edit_do_frontend_form_submission_shortcode' );

/**
 * Handles form submission on save. Redirects if save is successful, otherwise sets an error message as a cmb property
 *
 * @return void
 */
function bee_edit_handle_frontend_new_post_form_submission() {
	// If no form submission, bail
	if ( empty( $_POST ) || ! isset( $_POST['submit-cmb'], $_POST['object_id'] ) ) {
		return false;
	}
	// Get CMB2 metabox object
	$cmb = bee_edit_frontend_cmb2_get();
	$post_data = array();
	
	// Get our shortcode attributes and set them as our initial post_data args
	if ( isset( $_POST['atts'] ) ) {
		foreach ( (array) $_POST['atts'] as $key => $value ) {
			$post_data[ $key ] = sanitize_text_field( $value );
		}
		unset( $_POST['atts'] );
	}
	// Check security nonce
	if ( ! isset( $_POST[ $cmb->nonce() ] ) || ! wp_verify_nonce( $_POST[ $cmb->nonce() ], $cmb->nonce() ) ) {
		return $cmb->prop( 'submission_error', new WP_Error( 'security_fail', __( 'Security check failed.' ) ) );
	}
	// Check title submitted
	if ( empty( $_POST['bee_listing_title'] ) ) {
		return $cmb->prop( 'submission_error', new WP_Error( 'post_data_missing', __( 'New post requires a title.' ) ) );
	}
	// And that the title is not the default title
	
	/**
	 * Fetch sanitized values
	 */
	$sanitized_values = $cmb->get_sanitized_values( $_POST );
	// Set our post data arguments
	$post_data['post_title']   = $sanitized_values['bee_listing_title'];
	$post_data['ID']  = $_SESSION['beeid'];
	//$post_data['post_id']   = $sanitized_values['bee_listing_title'];
	//unset( $sanitized_values['bee_listing_title'] );
	//unset( $sanitized_values['bee_listing_category'] );
	//$post_data['post_content'] = $sanitized_values['bee_listing_description'];
	//unset( $sanitized_values['bee_listing_description'] );
	// Create the new post
	

 $new_submission_id=wp_update_post($post_data,true);
	
	
	// If we hit a snag, update the user
	if ( is_wp_error( $new_submission_id ) ) {
		return $cmb->prop( 'submission_error', $new_submission_id );
	}
	/**
	 * Other than post_type and post_status, we want
	 * our uploaded attachment post to have the same post-data
	 */
	unset( $post_data['post_type'] );
	unset( $post_data['post_status'] );
	// Try to upload the featured image
	$img_id = bee_frontend_form_photo_upload( $new_submission_id, $post_data );
	// If our photo upload was successful, set the featured image
	if ( $img_id && ! is_wp_error( $img_id ) ) {
		set_post_thumbnail( $new_submission_id, $img_id );
	}
	// Loop through remaining (sanitized) data, and save to post-meta
	foreach ( $sanitized_values as $key => $value ) {
		if ( is_array( $value ) ) {
			$value = array_filter( $value );
			if( ! empty( $value ) ) {
				update_post_meta( $new_submission_id, $key, $value );
			}
		} else {
			update_post_meta( $new_submission_id, $key, $value );
		}
	}
	/*
	 * Redirect back to the form page with a query variable with the new post ID.
	 * This will help double-submissions with browser refreshes
	 */
	wp_redirect( esc_url_raw( add_query_arg( 'post_submitted', $new_submission_id ) ) );
	exit;
}
add_action( 'cmb2_after_init', 'bee_edit_handle_frontend_new_post_form_submission' );
/**
 * Handles uploading a file to a WordPress post
 *
 * @param  int   $post_id              Post ID to upload the photo to
 * @param  array $attachment_post_data Attachement post-data array
 */
}




// require_once plugin_dir_path( __FILE__ ) .'bee-edit-listing.php';