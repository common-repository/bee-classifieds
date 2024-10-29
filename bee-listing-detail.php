<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
if (isset($_REQUEST['bee_listing_id'])){
// add_filter('wp_title','bee_callback');
 
 
 }

add_image_size( 'beeclassi-carousel-size', 730, 456, true );

function bee_post_title_seo( $title) {

  if (isset($_REQUEST['bee_listing_id'])){
    $title['site'] = get_the_title($_REQUEST['bee_listing_id']);
	
	}
   

    return $title;
}
add_filter( 'document_title_parts', 'bee_post_title_seo', 10000, 2 );
/////

function bee_image_carousel( $file_list_meta_key, $img_size = 'medium' ) {
$bee_listing_id = $_REQUEST['bee_listing_id'];
    // Get the list of files
    $files = get_post_meta($bee_listing_id, $file_list_meta_key, 1 );
    $bee_i=0; 
  
    // Loop through them and output an image
    foreach ( (array) $files as $attachment_id => $attachment_url ) {
	
	if($bee_i==0) {
            $bee_listing_class='item active';
        }
	else  {
       $bee_listing_class='item';
    }
   
        echo '<div class="'.$bee_listing_class.'">';
	 
		if (! $files){
		echo "<img src='https://placeholdit.imgix.net/~text?txtsize=38&txt=No+Image&w=600&h=350' />";
		}
		else
		
        echo wp_get_attachment_image( $attachment_id, $img_size );
        echo '</div>';
		
		 $bee_i++;
    }

 
}

function bee_image_carousel_control( $file_list_meta_key, $img_size = 'small' ) {
$bee_listing_id = $_REQUEST['bee_listing_id'];
    // Get the list of files
    $files = get_post_meta($bee_listing_id, $file_list_meta_key, 1 );


    $bee_i_c=0; 
  
    // Loop through them and output an image
    foreach ( (array) $files as $attachment_id => $attachment_url ) {
	
	if($bee_i_c==0) {
            $bee_data_slide='item active';
        }
	else  {
       $bee_listing_class='item';
    }
       echo "<li data-target='#carousel-custom' data-slide-to='".$bee_i_c."'>";
       
        echo wp_get_attachment_image( $attachment_id, $img_size );
		
		
        echo '</li>';
		
		 $bee_i_c++;
    }
 
}

function bee_listing_detail( $file_list_meta_key, $img_size = 'medium')
{ 

 ?>
<?php 
$bee_listing_id = $_REQUEST['bee_listing_id'];
$args = array('p' => $bee_listing_id, 'post_type' => 'beeclassifieds');
$loop = new WP_Query($args);
?>
<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
    <?php global $post;
	
	
	 ?>
    
   	 <div class="item  col-md-8 col-md-8 ">
	 <div class="caption">
                    <h3><?php echo get_the_title(); ?></h3></div>
           <div id='carousel-custom' class='carousel slide' data-ride='carousel'>
		   		
	<?php	if(cmb2_get_option('bee_classi_options', 'hide_price')=='no') { ?> 
		
				 <?php               
                   if(get_post_meta( get_the_ID(), 'bee_listing_price', true ))
				 {  
				 ?> <span class="badge beeprice">
                 <i class="fa fa-tag fa-lg"></i> <?php
				 
				    
                  echo cmb2_get_option('bee_classi_options', 'bee_currency_symbol' ).' ';  
				   echo get_post_meta( get_the_ID(), 'bee_listing_price', true );
				   }
				    
				    } 
					?></span>
					
					
    <div class='carousel-outer'>
        <!-- Wrapper for slides -->
        <div class='carousel-inner'>
           <?php bee_image_carousel( 'bee_listing_images', 'beeclassi-carousel-size' );  ?>
           
        
        </div>
            
        <!-- Controls -->
        <a class='left carousel-control' href='#carousel-custom' data-slide='prev'>
            <span class='glyphicon glyphicon-chevron-left'></span>
        </a>
        <a class='right carousel-control' href='#carousel-custom' data-slide='next'>
            <span class='glyphicon glyphicon-chevron-right'></span>
        </a>
    </div>
	
    
    <!-- Indicators -->
   <ol class='carousel-indicators '>
          <?php bee_image_carousel_control( 'bee_listing_images', 'thumb' );  ?>
    </ol>
</div>

             <div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#description" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-pencil-square-o fa-lg"></i> Description</a></li>
    <li role="presentation"><a href="#contact" aria-controls="contact" role="tab" data-toggle="tab">Contact</a></li>

  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="description">                  
    <div class="panel panel-default"><div class="panel-body"><?php echo get_post_meta( get_the_ID(), 'bee_listing_description', true );?></div></div></div>
	 <div role="tabpanel" class="tab-pane" id="contact">                  
    <div class="panel panel-default"><div class="panel-body">
	<p><i class="fa fa-map-marker fa-lg"></i> <?php echo get_post_meta( get_the_ID(), 'bee_listing_address', true );?></p>
	<p><i class="fa fa-phone fa-lg"></i>  <?php echo get_post_meta( get_the_ID(), 'bee_listing_phone', true );?></p>
	<p><i class="fa fa-envelope fa-lg"></i>  <?php echo get_post_meta( get_the_ID(), 'bee_listing_email', true );?> </p>
	<p><i class="fa fa-globe fa-lg"></i>  <a href="<?php echo get_post_meta( get_the_ID(), 'bee_listing_url', true );?>"><?php echo get_post_meta( get_the_ID(), 'bee_listing_url', true );?></a></p>
	</div></div></div>
   
  </div>   
                 
					 
					
                   
                      
                  </div>
     </div>      
           
<?php  endwhile; }