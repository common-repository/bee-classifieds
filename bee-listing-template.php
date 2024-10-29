<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
function bee_view_listings() {
$bee_search_term="";
if (!empty($_POST))

{


$bee_search_term=$_POST['listing_search'];
echo "Search result for   <b>".$bee_search_term.'</b>';
}
$user_id = get_current_user_id();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
 
$args = array(
   'posts_per_page' => cmb2_get_option('bee_classi_options', 'no_of_listing'),
   'paged' => $paged
);

$bee_query_args = array(
    'post_type' => 'beeclassifieds', 
	's' => $bee_search_term,
    'posts_per_page' => cmb2_get_option('bee_classi_options', 'no_of_listing'),
    'paged' => $paged,
	'author' => $user_id,
    'ignore_sticky_posts' => true,
    //'category_name' => 'custom-cat',
    'order' => 'DESC', // 'ASC'
    'orderby' => 'date' // modified | title | name | ID | rand
);
$bee_query = new WP_Query( $bee_query_args );
 ?>
 <form class="search-field" action="<?php echo get_permalink(); ?>" method="post">
  <input type="search" name="listing_search" placeholder="Search&hellip;">
  <input type="submit" value="Search">
  <input type="hidden" name="search_listing" value="beeclassifieds">

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
				   if(cmb2_get_option('bee_classi_options', 'enable_link')=='yes') {
				   
				   ?>
				   <a href="<?= add_query_arg('bee_listing_id', get_the_ID()); ?>"> 
				   
				   <?php
            echo '<img   src="'.$fitem.'"/></a>';
			}
       else{  echo '<img   src="'.$fitem.'"/>'; }
			}
				 $first_image++;
	}

	}
  
		else {
		$no_img=plugin_dir_url( __FILE__ )."public/images/no-image-available.png";
		
		
	echo '<img  src="'.$no_img.'"/>';
	
} ?>
<div class="caption">
                    <h3>
                      <?php echo  substr(get_the_title(),0, 50); ?></h3>					 
                    <p >
                       <?php echo substr(get_post_meta( get_the_ID(), 'bee_listing_description', true ),0 , 60);?></p>
                    <div class="row">
                   <?php if(cmb2_get_option('bee_classi_options', 'hide_price')=='no') { ?>    <div class="col-md-12 center-block">
                            <p class="text-center">
                               <b><?php echo cmb2_get_option('bee_classi_options', 'bee_currency_symbol' ); ?></b> <?php echo get_post_meta( get_the_ID(), 'bee_listing_price', true );?></p>
                        </div>
						<?php } ?>
                       
                    </div>
					<div class="row"> <div class="col-xs-12 col-md-12 text-center">
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
<?php if (function_exists("bee_pagination")) {
    bee_pagination($bee_query->max_num_pages);
} ?>
            
        </nav>
        <?php
        $wp_query = $orig_query; // fix for bee_pagination to work
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