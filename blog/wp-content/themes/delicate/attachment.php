<?php get_header();
$t_show_sidebar = nattywp_get_option("t_show_sidebar");
?>
<div id="main">		
	<div class="columns">
  <div class="narrowcolumn singlepage <?php echo $t_show_sidebar; ?>">
   <?php if (have_posts()) : ?>
     <?php while (have_posts()) : the_post(); ?>
				
      <div <?php post_class('post');?>>
        <div class="title">        
          <?php if ( ! empty( $post->post_parent ) ) : ?>
            <p class="page-title"><a href="<?php echo get_permalink( $post->post_parent ); ?>" title="<?php echo esc_attr( sprintf( __( 'Return to %s', 'delicate' ), strip_tags( get_the_title( $post->post_parent ) ) ) ); ?>" rel="gallery"><?php
						/* translators: %s - title of parent post */
						printf( __( '<span class="meta-nav">&larr;</span> %s', 'delicate' ), get_the_title( $post->post_parent ) );
					?></a></p>
          <?php endif; ?>
          
          <h2><?php the_title(); ?></h2>
          <small><?php the_time('F jS, Y') ?> | 
          <?php 
            if ( wp_attachment_is_image() ) {
							$metadata = wp_get_attachment_metadata();
							echo __('Full size: ', 'delicate');
							printf( '<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
									wp_get_attachment_url(),
									esc_attr( __( 'Link to full-size image', 'delicate' ) ),
									$metadata['width'],
									$metadata['height']
								);
						} ?>
					<?php edit_post_link(__('Edit','delicate'), ' | ', ''); ?></small> 
        </div>
      
        <div class="entry">
             <?php if ( wp_attachment_is_image() ) :
                  $attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
                  foreach ( $attachments as $k => $attachment ) {
                    if ( $attachment->ID == $post->ID )
                      break;
                  }
                  $k++;
                  // If there is more than 1 image attachment in a gallery
                  if ( count( $attachments ) > 1 ) {
                    if ( isset( $attachments[ $k ] ) )
                      // get the URL of the next image attachment
                      $next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
                    else
                      // or get the URL of the first image attachment
                      $next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
                  } else {
                    // or, if there's only 1 image attachment, get the URL of the image
                    $next_attachment_url = wp_get_attachment_url();
                  }
                ?>
                <p class="attachment">
                <a href="<?php echo $next_attachment_url; ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php
                              $attachment_width  = apply_filters( 'delicate_attachment_size', 620 );
                              $attachment_height = apply_filters( 'delicate_attachment_height', 900 );
                              echo wp_get_attachment_image( $post->ID, array( $attachment_width, $attachment_height ) ); ?></a></p>

                <div id="nav-below" class="navigation">
                    <div class="nav-previous"><?php previous_image_link( false ); ?></div>
                    <div class="nav-next"><?php next_image_link( false ); ?></div>
                </div><!-- #nav-below -->
                <div class="clear"></div>
                <?php else : ?>
                  <a href="<?php echo wp_get_attachment_url(); ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a>
                <?php endif; ?>
                
              <div class="e-caption"><?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?></div>
              <?php the_content(); ?>
              <div class="clear"></div>
        </div>
        <p class="postmetadata"><?php wp_link_pages(array('before' => '<p><strong>' . __('Pages:', 'delicate' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?></p> 
        </div>
        <div class="post">
        	<?php comments_template(); ?>      	
       	</div>
  <?php endwhile; ?>
  <?php else : ?>
    <div class="post">
       <h2><?php _e('Not Found','delicate'); ?></h2>
       <div class="entry"><p><?php _e('Sorry, but you are looking for something that isn\'t here.','delicate'); ?></p>
       <?php get_search_form(); ?>
       </div>
     </div>
  <?php endif; ?>

</div> <!-- END Narrowcolumn -->

<?php if ($t_show_sidebar == 'disable') {} else { ?>
  <div id="sidebar" class="profile <?php echo $t_show_sidebar; ?>">
      <?php if (!is_active_sidebar(2)) {
           get_sidebar(); 
         } else {
           echo '<ul>';
           dynamic_sidebar('sidebar-2');
           echo '</ul>';
         } ?>
  </div>
<?php } ?>
  
<div class="clear"></div>
<?php get_footer(); ?> 