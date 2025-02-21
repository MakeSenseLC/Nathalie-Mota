<?php
get_header();
?>

<main>
    <section>
        <h2><?php the_title(); ?></h2> <!-- Affiche le titre de la page -->
        <div><?php the_content(); ?></div> <!-- Affiche le contenu de la page -->
        <br>
        <br>
        <br>
        <br>
        <br>
    </section>
</main>

<?php get_footer(); ?>
