<?php get_header(); ?>

<h1>Photographe Event</h1>

<ul>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <li>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail(); ?>
            </a>
        </li>
    <?php endwhile; endif; ?>
</ul>

<?php get_footer(); ?>