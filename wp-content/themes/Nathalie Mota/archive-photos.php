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
            echo '<p>Aucune image disponible.</p>';
        }
    ?>
</div>
<section class="photo-filters">
    <form id="photo-filter">
        <select name="category" id="category-filter">
            <option value="">CATÉGORIES</option>
            <?php 
            $categories = get_terms(array('taxonomy' => 'category', 'hide_empty' => false, 'exclude' => array(1)));
            foreach ($categories as $category) {
                echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
            }
            ?>
        </select>
        <select name="format" id="format-filter">
            <option value="">FORMATS</option>
            <?php 
            $formats = get_terms(array('taxonomy' => 'formats', 'hide_empty' => false));
            foreach ($formats as $format) {
                echo '<option value="' . $format->term_id . '">' . $format->name . '</option>';
            }
            ?>
        </select>
    </form>
    <form id="photo-date-filter">
        <select name="date" id="date-filter">        
            <option value="">TRIER PAR</option>
            <option value="newest">Les plus récentes</option>
            <option value="oldest">Les plus anciennes</option>
        </select>
    </form>
</section>
<section class="photo-list">
    <?php 
        $args = array(
            'post_type' => 'photos',
            'posts_per_page' => 8,
        );
        $my_query = new WP_Query( $args );

        if( $my_query->have_posts() ) : 
            while( $my_query->have_posts() ) : $my_query->the_post();
        get_template_part('templates/photo-block');
        endwhile; endif; 
    ?>
</section>
<div class="load-more-zone">
<p class="load-more">Charger plus</p>
</div>

<?php get_footer(); ?>