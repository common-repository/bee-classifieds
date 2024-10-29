<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
require_once plugin_dir_path( __FILE__ ) .'includes/framework/bee_config.php';
require_once plugin_dir_path( __FILE__ ) .'includes/framework/bee-classi-options.php';
if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}
function beeclassi_taxonomy() {
// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI
  $labels = array(
    'name' => _x( 'Listing Category', 'listing category' ),
    'singular_name' => _x( 'Listing Category', 'listing category' ),
    'search_items' =>  __( 'Search Listing Category' ),
    'all_items' => __( 'All Listing Categories' ),
    'parent_item' => __( 'Parent Listing Category' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item' => __( 'Edit Listing Category' ), 
    'update_item' => __( 'Update Listing Category' ),
    'add_new_item' => __( 'Add New Listing Category' ),
    'new_item_name' => __( 'New Listing Category Name' ),
    'menu_name' => __( 'Listing Categories' ),
  ); 	

// Now register the taxonomy

register_taxonomy(
		'listingcat',
		'beeclassifieds',
		array(
			'label' => 'Categories',
			'hierarchical' => true,
			'show_ui'           => true,
             'show_admin_column' => true,
			 'post_type' => 'beeclassifieds',
		)
	);

}
add_action( 'init', 'beeclassi_taxonomy',0);
add_action( 'init', 'bee_post_type', 0 );



function bee_post_type() {

	$labels = array(
		'name'                  => _x( 'Listings', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Listing', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Bee Classi', 'text_domain' ),
		'name_admin_bar'        => __( 'Bee Classi', 'text_domain' ),
		'archives'              => __( 'Listing Archives', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Listing:', 'text_domain' ),
		'all_items'             => __( 'All Listings', 'text_domain' ),
		'add_new_item'          => __( 'Add New Listing', 'text_domain' ),
		'add_new'               => __( 'Add Listing', 'text_domain' ),
		'new_item'              => __( 'New Listing', 'text_domain' ),
		'edit_item'             => __( 'Edit Listing', 'text_domain' ),
		'update_item'           => __( 'Update Listing', 'text_domain' ),
		'view_item'             => __( 'View Listing', 'text_domain' ),
		'search_items'          => __( 'Search Listing', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into Listing', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Listing', 'text_domain' ),
		'items_list'            => __( 'Listings list', 'text_domain' ),
		'items_list_navigation' => __( 'Listings list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter Listings list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Listing', 'text_domain' ),
		'description'           => __( 'Add Your Listings', 'text_domain' ),
		'labels'                => $labels,
		'supports'              =>  array( 'title','listingcat','thumbnail'),
		'hierarchical'          => TRUE,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'beeclassifieds', $args );

}

add_action( 'cmb2_admin_init', 'beeclassi_metaboxes' );

///Admin Category filter
add_action('restrict_manage_posts', 'bee_filter_post_type_by_taxonomy');
function bee_filter_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'beeclassifieds'; // change to your post type
	$taxonomy  = 'listingcat'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => __("Show All {$info_taxonomy->label}"),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}
add_filter('parse_query', 'bee_convert_id_to_term_in_query');
function bee_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'beeclassifieds'; // change to your post type
	$taxonomy  = 'listingcat'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}
/**
 * Define the metabox and field configurations.
 */
function beeclassi_metaboxes() {

    // Start with an underscore to hide fields from custom fields list
    $prefix = 'bee_';

    /**
     * Initiate the metabox
     */
	
    $cmb = new_cmb2_box( array(
        'id'            => 'test_metabox',
        'title'         => __( 'Listing Info', 'cmb2' ),
        'object_types'  => array( 'beeclassifieds', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ) );
	
		
	$cmb->add_field( array(
		'name'    => __( 'Listing Description', 'cmb2' ),
		'id'      => 'bee_listing_description',
		'type'    => 'textarea_small',
		 'attributes'  => array(
        'required'    => 'required',
    ),
		
	) );
	

      // Decription Field
    $cmb->add_field( array(
        'name'       => __( 'Listing Description', 'cmb2' ),
        'desc'       => __( 'field description (optional)', 'cmb2' ),
        'id'         => $prefix . 'listing_description',
		
        'type'       => 'textarea_small',
        'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
        // 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
        // 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
        // 'on_front'        => false, // Optionally designate a field to wp-admin only
        // 'repeatable'      => true,
    ) );
	
	  // Listing Price
    $cmb->add_field( array(
    'name' => 'Price',
    'desc' => 'if your listing item for sale then type the  price',
    'id' => $prefix . 'listing_price',
    'type' => 'text'
    ) );
	
	// Listing Images
   $cmb->add_field( array(
    'name' => 'Listing Images',
    'desc' => 'upload your listing images',
    'id' => $prefix . 'listing_images',
    'type' => 'file_list',
    // 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
    ) );
	
	// Listing Contact Number
	$cmb->add_field( array(
		'name'    => __( 'Contact Number', 'wds-post-submit' ),
		'id'      => 'bee_listing_phone',
		'type'    => 'text',
		
			) );
	// Listing Address
	 $cmb->add_field( array(
        'name'       => __( 'Contact Address ', 'cmb2' ),
        'desc'       => __( 'field description (optional)', 'cmb2' ),
        'id'         => $prefix . 'listing_address',
        'type'       => 'textarea_small',
        'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
        // 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
        // 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
        // 'on_front'        => false, // Optionally designate a field to wp-admin only
        // 'repeatable'      => true,
    ) );
	
    // Website URL
    $cmb->add_field( array(
        'name' => __( 'Website URL', 'cmb2' ),
        'desc' => __( 'Enter Your Website url(optional)', 'cmb2' ),
        'id'   => $prefix . 'listing_url',
        'type' => 'text_url',
        // 'protocols' => array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet'), // Array of allowed protocols
        // 'repeatable' => true,
    ) );
	
	

    // Listing Email
    $cmb->add_field( array(
        'name' => __( 'Email', 'cmb2' ),
        'desc' => __( 'Enter your email', 'cmb2' ),
        'id'   => $prefix . 'listing_email',
        'type' => 'text_email',
		
        // 'repeatable' => true,
    ) );

    // Add other metaboxes as needed

}

//FRONT AD POSTING FUNCTION//////////////////

function bee_get_term_options( $taxonomy = 'listingcat', $args = array() ) {



    $args['taxonomy'] = $taxonomy;
    $defaults = array( 'taxonomy' => 'listingcat','hide_empty' => false, );
    $args = wp_parse_args( $args,$defaults );

    $taxonomy = $args['taxonomy'];

    $terms = (array) get_terms( $taxonomy, $args );

    // Initate an empty array
    $term_options = array();
    if ( ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
        $term_options[ $term->name ] = $term->name;
		  
        }
    }

    return $term_options;
	
}


function bee_frontend_form_register() {
	$cmb = new_cmb2_box( array(
		'id'           => 'front-end-post-form',
		'object_types' => array( 'beeclassifieds' ),
		'hookup'       => false,
		'save_fields'  => false,
		
	) );
	$cmb->add_field( array(
		'name'    => __( 'New Listing Title', 'wds-post-submit' ),
		'id'      => 'bee_listing_title',
		'type'    => 'text',
		
		 'attributes'  => array(
        'required'    => 'required',
    ),
	) );
	
	$cmb->add_field( array(
    'name'     => __( 'Listing Category', 'wds-post-submit' ),
    'desc'     => 'Selct your listing category',
	'show_option_none' => false,
    'default'          => 'custom',
    'id'       => 'bee_listing_category',
    'type'    => 'select',
     'options'          => bee_get_term_options(),
   
) );


	$cmb->add_field( array(
		'name'    => __( 'Listing Description', 'wds-post-submit' ),
		'id'      => 'bee_listing_description',
		'type'    => 'textarea_small',
		 'attributes'  => array(
        'required'    => 'required',
    ),
		
	) );
	
	$cmb->add_field( array(
		'name' => __( 'Price', 'wds-post-submit' ),
		'desc' => __( 'If your listing item for sale then enter your price', 'wds-post-submit' ),
		'id'   => 'bee_listing_price',
		'type' => 'text',
	) );
	
	$cmb->add_field( array(
		'name' => __( 'Upload Images', 'cmb2' ),
		'desc' => __( 'Upload your listing images', 'wds-post-submit' ),
		'id'   => 'bee_listing_images',
		'type' => 'file_list',
	) );
	
	// Listing Contact Number
	$cmb->add_field( array(
		'name'    => __( 'Contact Number', 'wds-post-submit' ),
		'id'      => 'bee_listing_phone',
		'type'    => 'text',
		
	) );
		$cmb->add_field( array(
		'name' => __( 'Contact Address', 'cmb2' ),
		'desc' => __( 'Enter your contact address', 'wds-post-submit' ),
		'id'   => 'bee_listing_address',
		'type' => 'textarea_small',
		 'attributes'  => array(
        'required'    => 'required',
    ),
	) );
	
		$cmb->add_field( array(
		'name' => __( 'Website Url', 'cmb2' ),
		'desc' => __( 'Enter your website url', 'wds-post-submit' ),
		'id'   => 'bee_listing_url',
		'type' => 'text_url',
	) );
	$cmb->add_field( array(
		'name' => __( 'Your Email', 'cmb2' ),
		'desc' => __( 'Please enter your email so we can contact you if we use your post.', 'wds-post-submit' ),
		'id'   => 'bee_listing_email',
		'type' => 'text_email',
		 'attributes'  => array(
        'required'    => 'required',
    ),
	) );
}
add_action( 'cmb2_init', 'bee_frontend_form_register' );
/**
 * Gets the front-end-post-form cmb instance
 *
 * @return CMB2 object
 */
 
 
function bee_frontend_cmb2_get() {
	// Use ID of metabox in bee_frontend_form_register
	$metabox_id = 'front-end-post-form';
	// Post/object ID is not applicable since we're using this form for submission
	$object_id  = 'fake-oject-id';
	// Get CMB2 metabox object
	return cmb2_get_metabox( $metabox_id, $object_id );
}
/**
 * Handle the cmb-frontend-form shortcode
 *
 * @param  array  $atts Array of shortcode attributes
 * @return string       Form html
 */
 
 function bee_auth(){
 printf( '<a href="%s">%s</a>', 
        wp_login_url( get_permalink() ),
        __( 'You need to login to post listings!' )
    );

}
function bee_frontend_form_submission_shortcode( $atts = array() ) {
	// Get CMB2 metabox object
	$cmb = bee_frontend_cmb2_get();
	// Get $cmb object_types
	$post_types = $cmb->prop( 'object_types' );
	// Current user
	$user_id = get_current_user_id();
	// Parse attributes
	$atts = shortcode_atts( array(
		'post_author' => $user_id ? $user_id : 1, // Current user, or admin
		'post_status' => 'pending',
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
		$output .= '<h3 class="bee-error-msg">' . sprintf( __( 'There was an error in the submission: %s', 'wds-post-submit' ), '<strong>'. $error->get_error_message() .'</strong>' ) . '</h3>';
	}
	// If the post was submitted successfully, notify the user.
	if ( isset( $_GET['post_submitted'] ) && ( $post = get_post( absint( $_GET['post_submitted'] ) ) ) ) {
		// Get submitter's name
		$name = get_post_meta( $post->ID, 'submitted_author_name', 1 );
		$name = $name ? ' '. $name : '';
		// Add notice of submission to our output
		$output .= '<h3 class="bee-success-msg">' . sprintf( __( 'Thank you%s, your new post has been submitted and is pending review by a site administrator.', 'wds-post-submit' ), esc_html( $name ) ) . '</h3>';
	}
	// Get our form
	$output .= cmb2_get_metabox_form( $cmb, 'fake-oject-id', array( 'save_button' => __( 'Save Listing', 'wds-post-submit' ) ) );
	return $output;
}

    $user = wp_get_current_user();
 
if( is_user_logged_in() )
{
   


add_shortcode( 'bee-add-listing', 'bee_frontend_form_submission_shortcode' );

}
else



add_shortcode( 'bee-add-listing', 'bee_auth' );
/**
 * Handles form submission on save. Redirects if save is successful, otherwise sets an error message as a cmb property
 *
 * @return void
 */
function bee_frontend_new_post_form_submission() {
	// If no form submission, bail
	if ( empty( $_POST ) || ! isset( $_POST['submit-cmb'], $_POST['object_id'] ) ) {
		return false;
	}
	// Get CMB2 metabox object
	$cmb = bee_frontend_cmb2_get();
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
	if ( $cmb->get_field( 'bee_listing_title' )->default() == $_POST['bee_listing_title'] ) {
		return $cmb->prop( 'submission_error', new WP_Error( 'post_data_missing', __( 'Please enter a new title.' ) ) );
	}
	/**
	 * Fetch sanitized values
	 */
	$sanitized_values = $cmb->get_sanitized_values( $_POST );
	// Set our post data arguments
	$post_data['post_title']   = $sanitized_values['bee_listing_title'];
	unset( $sanitized_values['bee_listing_title'] );
	//unset( $sanitized_values['bee_listing_category'] );
	//$post_data['post_content'] = $sanitized_values['bee_listing_description'];
	//unset( $sanitized_values['bee_listing_description'] );
	// Create the new post
	$beecat=$_POST['bee_listing_category'];
	
	
	$new_submission_id = wp_insert_post( $post_data, true );
	
	
		wp_set_object_terms( $new_submission_id , $beecat, 'listingcat');


	
	
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
add_action( 'cmb2_after_init', 'bee_frontend_new_post_form_submission' );
/**
 * Handles uploading a file to a WordPress post
 *
 * @param  int   $post_id              Post ID to upload the photo to
 * @param  array $attachment_post_data Attachement post-data array
 */
function bee_frontend_form_photo_upload( $post_id, $attachment_post_data = array() ) {
	// Make sure the right files were submitted
	if (
		empty( $_FILES )
		|| ! isset( $_FILES['submitted_post_thumbnail'] )
		|| isset( $_FILES['submitted_post_thumbnail']['error'] ) && 0 !== $_FILES['submitted_post_thumbnail']['error']
	) {
		return;
	}
	// Filter out empty array values
	$files = array_filter( $_FILES['submitted_post_thumbnail'] );
	// Make sure files were submitted at all
	if ( empty( $files ) ) {
		return;
	}
	// Make sure to include the WordPress media uploader API if it's not (front-end)
	if ( ! function_exists( 'media_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
	}
	// Upload the file and send back the attachment post ID
	return media_handle_upload( 'submitted_post_thumbnail', $post_id, $attachment_post_data );
}

///ADMIN COLUMN

add_image_size( 'admin-list-thumb', 80, 80, false );

// Add the posts and pages columns filter. They can both use the same function.

add_filter('manage_beeclassifieds_posts_columns', 'bee_add_post_thumbnail_column', 5);
add_filter('manage_beeclassifieds_pages_columns', 'bee_add_post_thumbnail_column', 5);

// Add the column
function bee_add_post_thumbnail_column($cols){
  $cols['tcb_post_thumb'] = __('Featured Image');
  return $cols;
}

// Hook into the posts an pages column managing. Sharing function callback again.
add_action('manage_beeclassifieds_posts_custom_column', 'bee_display_post_thumbnail_column', 5, 2);
add_action('manage_beeclassifieds_pages_custom_column', 'bee_display_post_thumbnail_column', 5, 2);

// Grab featured-thumbnail size post thumbnail and display it.
function bee_display_post_thumbnail_column($col, $id){
 	 
				 $first_image=0;
				  
				  $listing_front_image= get_post_meta( get_the_ID(), 'bee_listing_images', true );
				if(!empty($listing_front_image)){
				  
				  foreach ($listing_front_image as $fitem){
				 
				  if($first_image==0) {
            echo '<img height="80"  src="'.$fitem.'"/>';
       
			}
				 $first_image++;
	}

	}
  
		else {
		$no_img=plugin_dir_url( __FILE__ )."public/images/no-image-available.png";
	echo '<img height="80"    src="'.$no_img.'"/>';
} 
}
//Include Requored Files
 require_once plugin_dir_path( __FILE__ ) .'my-listings.php';
 require_once plugin_dir_path( __FILE__ ) .'bee-edit-listing.php';
 require_once plugin_dir_path( __FILE__ ) .'bee-listing-template.php';
 require_once plugin_dir_path( __FILE__ ) .'bee-listing-detail.php';
 require_once plugin_dir_path( __FILE__ ) .'bee-pagination.php';	
///Listing thumnail size	
add_image_size( 'listing-thumb', 400, 250, array( 'left', 'top' ) ); // Hard crop left top
