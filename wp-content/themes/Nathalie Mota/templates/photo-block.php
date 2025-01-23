<?php 
$args = array(
    'post_type' => 'photos',
    'category_name' => get_the_category()[0]->slug,
    'posts_per_page' => 2,
);

$my_query = new WP_Query( $args );

if( $my_query->have_posts() ) : while( $my_query->have_posts() ) : $my_query->the_post();
?> 
	<div class="photo-block">
        <a href="<?php the_permalink(); ?>">
    	    <?php the_post_thumbnail(); ?>
        </a>
	</div>
<?php
endwhile;
endif;

wp_reset_postdata();
?>