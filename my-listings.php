<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
 if(!isset($_REQUEST['i']))  {

$beemode='bee_edit_frontend_form_register';
function bee_edit_frontend_form_register() {
$user_id = get_current_user_id();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
 
$args = array(
   'posts_per_page' => 6,
   'paged' => $paged
);

$bee_custom_query_args = array(
    'post_type' => 'beeclassifieds', 
    'posts_per_page' => 5,
    'paged' => $paged,
	'author' => $user_id,
    'ignore_sticky_posts' => true,
    //'category_name' => 'custom-cat',
    'order' => 'DESC', // 'ASC'
    'orderby' => 'date' // modified | title | name | ID | rand
);
$bee_custom_query = new wp_query( $bee_custom_query_args );
 ?><form method="get"  action=""   >
 
 <table>
 
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
	<?php 
	
	add_filter('query_vars', 'bee_p_query');

    function bee_p_query($qvars) {
        $qvars[] = 'i';
        return $qvars;
    }

    
if ( $bee_custom_query->have_posts() ) :
    while( $bee_custom_query->have_posts() ) : $bee_custom_query->the_post(); ?>
         <tr>
    <td><?php echo get_the_title(); ?></td>
    <td><?php echo get_post_meta( get_the_ID(), 'bee_listing_description', true );?></td>
    <td><?php echo get_post_status( get_the_ID() ) ?></td>
	<input type="hidden" id="bee_edit_id" name="bee_edit_id" value="<?php echo get_post_meta( the_ID());?>" />
	
    <td>
	
	 <a href="<?= add_query_arg('i', get_the_ID()); ?>">Edit</a>
	<a onclick="return confirm('Are you sure you wish to delete listing: <?php echo get_the_title() ?>?')" href="<?php echo get_delete_post_link( get_the_ID() ); ?>">Delete</a></td>
</tr>

    <?php
    endwhile;
    ?>
</table></form>
    <?php if ($bee_custom_query->max_num_pages > 1) : // custom pagination  ?>
        <?php
       
        $wp_query = $bee_custom_query;
		 $bee_orig_query = $wp_query; // fix for pagination to work
        ?>
        <nav>
<?php if (function_exists("pagination")) {
    pagination($bee_custom_query->max_num_pages);
} ?>
            
        </nav>
        <?php
        $wp_query = $bee_orig_query; // fix for pagination to work
        ?>
    <?php endif; ?>

<?php
   wp_reset_postdata(); // reset the query 
else:
    echo '<p>'.__('Sorry, no posts matched your criteria.').'</p>';
endif;

}

}
else {

$beemode='bee_edit_frontend_form_register';

}
function bee_check_edit_page()
{
}
if(! isset($_REQUEST['i']))  {
add_shortcode( 'bee-edit-listing', 'bee_check_edit_page' );
}

if( is_user_logged_in() )
{
   
add_shortcode('bee-my-listings',$beemode);

}
else 

add_shortcode( 'bee-my-listings', 'bee_auth' );

