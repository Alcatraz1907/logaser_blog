<div id="main"> 
	<?php echo wpamp_page_header(); ?>
    <?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
        <article class="post">
        	
        	<?php if ( is_single() || is_page() ) { ?>
            	<h1 class="title"><?php the_title(); ?></h1>
            <?php } else { ?>
                <h2 class="title"><a href="<?php echo wp_amp_permalink(get_the_ID(), AMP_CONSTANT); ?>"><?php the_title(); ?></a></h2>
        	<?php } ?>
            
            <ul class="amp-wp-meta">
            	<li class="amp-wp-byline">
					<?php if ( function_exists( 'get_avatar_url' ) ) : ?>
	                    <amp-img src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'user_email' ), array('size' => 24,) ) ); ?>" width="24" height="24" layout="fixed"></amp-img>
                    <?php endif; ?>
                    By <span class="amp-wp-author"><a><?php echo esc_html( get_the_author() ); ?></a></span>
                    <?php $the_time = date_i18n( get_option( 'date_format' ), strtotime( get_the_time('F jS, Y') ) ); ?>
                    on <time datetime="<?php echo $the_time; ?>"><?php echo $the_time; ?></time>
                </li>
            </ul>
            <div class="clearfix"></div>
            
            <?php
				if ( is_single() || is_page() ) {
					echo wpamp_get_featured_image( get_post_thumbnail_id(), 'large' );
					echo wpamp_get_the_content( get_the_content() );
				} else { 
					echo wpamp_get_featured_image( get_post_thumbnail_id(), 'thumbnail' );
					echo wpamp_get_the_excerpt( get_the_excerpt(), 150 );
				} 
			?>
            <div class="clearfix"></div>
            
            <?php do_action( 'wpamp_display_categories' ); ?>
                
			<?php if ( is_single() || is_page() ) { ?>
                <div id="amp-pagination">
                	<?php $prev_post = get_previous_post(); if (!empty( $prev_post )): ?>
                    	<div class="prev"><a href="<?php echo wp_amp_permalink($prev_post->ID, AMP_CONSTANT); ?>"> &laquo; <?php echo $prev_post->post_title ?></a></div>
					<?php endif ?>
					<?php  $next_post = get_next_post(); if (!empty( $next_post )): ?>
                        <div class="next"><a href="<?php echo wp_amp_permalink($next_post->ID, AMP_CONSTANT); ?>"><?php echo $next_post->post_title ?> &raquo; </a></div>
					<?php endif ?>
                    <div class="clearfix"></div>
                </div>
            <?php } ?>
            
        </article>
    <?php endwhile; ?>
	<?php
        if ( !is_single() && !is_page() ) { 
            the_posts_pagination( array(
                'mid_size'          => __( '3', 'wp-amp-ninja' ),
                'screen_reader_text'          => __( '', 'wp-amp-ninja' ),
                'prev_text'          => __( 'Previous', 'wp-amp-ninja' ),
                'next_text'          => __( 'Next', 'wp-amp-ninja' ),
            ) );
        }
    ?> 
    <?php endif; ?>
</div>