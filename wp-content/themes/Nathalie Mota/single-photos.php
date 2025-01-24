<?php get_header(); ?>
<div class="border-gradient"></div>
<main>
	<section class="photo-top">
		<div class="infos-photo">
			<h2><?php the_title(); ?></h2>
			<ul class="description_photo">
				<li>Référence : <?php the_field('reference'); ?></li>
				<li>Catégorie : <?php the_terms(get_the_ID(), 'category'); ?></li>
				<li>Format : <?php the_terms(get_the_ID(), 'formats'); ?></li>
				<li>Type : <?php the_field('type_photo'); ?></li>
				<li>Année : <?php the_field('annee'); ?></li>
			</ul>
		</div>
		<?php the_post_thumbnail(); ?>
</section>
	<section class="photo-middle">
		<div class="photo-interest">
		<p>Cette photo vous intéresse ?</p>
		<a href="" class="contact-photo modal">Contact</a>
		</div>
		<div class="nav-photo">
			<?php
			// Obtenir les posts suivant et précédent
			$next_post = get_next_post();
			$previous_post = get_previous_post();

			// Si pas de post suivant, prendre le premier
			if (!$next_post) {
				$next_post = get_custom_boundary_photo_post('first');
			}

			// Si pas de post précédent, prendre le dernier
			if (!$previous_post) {
				$previous_post = get_custom_boundary_photo_post('last');
			}
			// Récupération des URLs des images
			$next_thumb_url = get_the_post_thumbnail_url($next_post->ID, 'thumbnail');
			$prev_thumb_url = get_the_post_thumbnail_url($previous_post->ID, 'thumbnail');
			?>
			<div class="nav-photo-block">
				<div class="nav-photo-thumbnail">
					<?php if (!empty($next_post)) : ?>
						<img id="preview-image" 
							src="<?php echo esc_url($next_thumb_url); ?>" 
							alt="Aperçu"
							data-next="<?php echo esc_url($next_thumb_url); ?>"
							data-prev="<?php echo esc_url($prev_thumb_url); ?>">
					<?php endif; ?>
				</div>
				<div class="nav-photo-arrows">
					<?php if (!empty($previous_post)) : ?>
						<a href="<?php echo esc_url(get_permalink($previous_post->ID)); ?>" class="arrow-link prev-arrow">
							<img class="left-arrow" 
								src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/left_arrow.png" 
								alt="Flèche vers la gauche">
						</a>
					<?php endif; ?>
					
					<?php if (!empty($next_post)) : ?>
						<a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="arrow-link next-arrow">
							<img class="right-arrow" 
								src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/right_arrow.png" 
								alt="Flèche vers la droite">
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<section class="photo-bottom">
		<h3> Vous aimerez aussi </h3>
		<div class="photo-list">
			<?php 
			$args = array(
				'post_type' => 'photos',
				'category_name' => get_the_category()[0]->slug,
				'posts_per_page' => 2,
				'post__not_in' => array(get_the_ID()),
			);

			$my_query = new WP_Query( $args );

			if( $my_query->have_posts() ) : 
				while( $my_query->have_posts() ) : $my_query->the_post();
			?> 
				<?php get_template_part('templates/photo-block'); ?>
			<?php
			endwhile;
			else :
				?>
					<p>Il n'y a pas d'autres photos dans cette catégorie.</p>
				<?php
			endif;
			wp_reset_postdata();
			?>
		</div>		
	</section>
<!--Récupération de la valeur de la balise meta reference -->
	<?php
$reference_value = get_post_meta(get_the_ID(), 'reference', true);
?>
<script>
    var photoReference = "<?php echo esc_js($reference_value); ?>";
</script>

</main>

<?php get_footer(); ?>