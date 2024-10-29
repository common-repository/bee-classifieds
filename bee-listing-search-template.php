<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
function bee_view_listings() {
$user_id = get_current_user_id();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
 
$args = array(
   'posts_per_page' => 6,
   'paged' => $paged
);

$bee_query_args = array(
    'post_type' => 'beeclassifieds', 
    'posts_per_page' => 6,
    'paged' => $paged,
	'author' => $user_id,
    'ignore_sticky_posts' => true,
    //'category_name' => 'custom-cat',
    'order' => 'DESC', // 'ASC'
    'orderby' => 'date' // modified | title | name | ID | rand
);
$bee_query = new WP_Query( $bee_query_args );
 ?><form method="get"  action=""   >
<div class="container-fluid">
    <div class="well well-sm">
        <strong>Listings</strong>
        <div class="btn-group">
            <a href="#" id="list" class="btn btn-default btn-sm"><i class="fa fa-list" aria-hidden="true"></i> List</a> <a href="#" id="grid" class="btn btn-default btn-sm"><i class="fa fa-th" aria-hidden="true"></i> Grid</a>
        </div>
    </div>
    <div id="products" class="row list-group">
	<?php 	
	add_filter('query_vars', 'p_query');
    function p_query($qvars) {
        $qvars[] = 'i';
        return $qvars;
    }
    
if ( $bee_query->have_posts() ) :
    while( $bee_query->have_posts() ) : $bee_query->the_post(); ?> 
		<div class="item  col-xs-4 col-lg-4">
            <div class="thumbnail">
               
				  <?php
			 	 $first_image=0;				  
				 $listing_front_image= get_post_meta( get_the_ID(), 'bee_listing_images', true );
				if(!empty($listing_front_image)){
				  
				  foreach ($listing_front_image as $fitem){
				 
				  if($first_image==0) {
            echo '<img  src="'.$fitem.'"/>';
       
			}
				 $first_image++;
	}

	}
  
		else {
		$no_img=plugin_dir_url( __FILE__ )."public/images/no-image-available.png";
	echo '<img  src="'.$no_img.'"/>';
	
} ?>
<div class="caption">
                    <h4 >
                         <?php echo get_the_title(); ?></h4>
                    <p  class="group inner list-group-item-text">
                       <?php echo get_post_meta( get_the_ID(), 'bee_listing_description', true );?></p>
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <p >
                               <?php echo get_post_meta( get_the_ID(), 'bee_listing_price', true );?></p>
                        </div>
                       
                    </div>
					<div class="row"> <div class="col-xs-12 col-md-6">
                            <a class="btn btn-default" href="<?= add_query_arg('bee_listing_id', get_the_ID()); ?>">View Details</a>
                   </div></div>
                </div>
            </div>
        </div>

			
         <?php
    endwhile;
    ?>
      </div>

</div>

</form>
    <?php if ($bee_query->max_num_pages > 1) : // custom pagination  ?>
        <?php
		 $wp_query = $bee_query;
        $orig_query = $wp_query; // fix for pagination to work
       
        ?>
        <nav>
<?php if (function_exists("pagination")) {
    pagination($bee_query->max_num_pages);
} ?>
            
        </nav>
        <?php
        $wp_query = $orig_query; // fix for pagination to work
        ?>
    <?php endif; ?>

<?php

    wp_reset_postdata(); // reset the query 
else:
    echo '<p>'.__('Sorry, no posts matched your criteria.').'</p>';
endif;

}

$beemode='bee_edit_frontend_form_register';

if(isset($_GET['bee_listing_id']))
{
$_SESSION['bee_listing_id']=$_GET['bee_listing_id'];

$bee_get_listing_id=$_SESSION['bee_listing_id'];


add_shortcode('bee-view-listing','bee_listing_detail');


   }
   
   else
{
add_shortcode('bee-view-listing','bee_view_listings');
}