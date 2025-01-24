<?php get_header(); ?>

<?php
query_posts(array('post_type' => 'photos'));
?>

<div class="hero">
    <h1>PHOTOGRAPHE EVENT</h1>
    <?php
        // Requête personnalisée pour récupérer un post aléatoire de la catégorie "paysage"
        $random_paysage_query = new WP_Query(array(
            'post_type' => 'photos',
            'tax_query' => array(
                array(
                    'taxonomy' => 'formats', // Nom de la taxonomie
                    'field'    => 'slug',   // Recherche par slug
                    'terms'    => 'paysage' // Slug du terme "Paysage"
                ),
            ),
            'posts_per_page' => 1,       // Limite à un post
            'orderby' => 'rand'          // Aléatoire
        ));

        // Vérifie si un post correspondant est trouvé
        if ($random_paysage_query->have_posts()) {
            while ($random_paysage_query->have_posts()) {
                $random_paysage_query->the_post();

                // Affiche l'image mise en avant du post aléatoire
                if (has_post_thumbnail()) {
                    echo '<div class="random-paysage-image">';
                    the_post_thumbnail('large'); // Taille de l'image (modifiable)
                    echo '</div>';
                }
            }

            // Réinitialise les données de la requête pour éviter des conflits
            wp_reset_postdata();
        } else {
            // Affiche un message si aucune image n'est trouvée
            echo '<p>Aucune image de paysage disponible.</p>';
        }
    ?>
</div>
<section class="photo-list">
    <?php if (have_posts()) : while (have_posts()) : the_post();
        get_template_part('templates/photo-block');
        endwhile; endif; 
    ?>
</section>

<?php get_footer(); ?>