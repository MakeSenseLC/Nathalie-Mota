<div class="photo-block">
	<a href="<?php the_permalink(); ?>">
		<?php the_post_thumbnail(); ?>
		<div class="photo-block-overlay">
			<div class="overlay-top-line">
				<span class="full-screen">&#9974;</span>
			</div>
				<img class="eye-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/icon_eye.png" alt="Oeil">
			<div class="overlay-bottom-line">
				<span class="overlay-ref"><?php the_field('reference'); ?></span>
				<span class="overlay-category"><?php
					$terms = get_the_terms(get_the_ID(), 'category');
					foreach ($terms as $term) {
						echo $term->name . ' ';
					}
				?></span>
			</div>
		</div>
	</a>
</div>