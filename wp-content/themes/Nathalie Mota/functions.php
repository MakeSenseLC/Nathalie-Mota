<?php 

// Ajouter la prise en charge des images mises en avant
add_theme_support('post-thumbnails' );

// Ajouter automatiquement le titre du site dans l'en-tête du site
add_theme_support( 'title-tag' );

//Ajout du menu
function declarer_emplacement_menu() {
  register_nav_menu('principal', __('Menu principal'));
}
add_action('after_setup_theme', 'declarer_emplacement_menu');

//Déclation des actions Ajax
add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos'); // Pour les utilisateurs non connectés

//Chargement du fichier CSS
function enqueue_custom_css() {
    wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_css' );

//Prise en charge du logo dans l'administration
function custom_theme_setup() {
    add_theme_support( 'custom-logo' );
}
add_action( 'after_setup_theme', 'custom_theme_setup' );

// Chargement du fichier JS
function enqueue_scripts() {
    wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/scripts/script.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );

// Enregistrer et charger jQuery inclus par WordPress
function enqueue_jquery_in_theme() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'enqueue_jquery_in_theme');

// Gérer la récupération des premiers et derniers posts pour la navigation dans la single-photos
function get_custom_boundary_photo_post($position = 'first') {
    $args = array(
        'post_type' => 'photos',
        'posts_per_page' => 1,
        'order' => ($position === 'first') ? 'ASC' : 'DESC',
        'orderby' => 'date'
    );
    
    $query = new WP_Query($args);
    return !empty($query->posts) ? $query->posts[0] : null;
}

// GESTION DU LOAD-MORE SUR LA PAGE D'ACCUEIL
function add_ajax_url_to_js() {
    wp_localize_script('script', 'ajax_loadmore', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'add_ajax_url_to_js');


function load_more_photos() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = 8;

    $args = array(
        'post_type'      => 'photos',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
    );

    $query = new WP_Query($args);
    $total_posts = $query->found_posts; // Nombre total d'articles
    $max_pages = ceil($total_posts / $posts_per_page); // Nombre max de pages

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('templates/photo-block');
        endwhile;
    endif;

    wp_reset_postdata();

    // Indique à JS si on est à la dernière page
    if ($paged >= $max_pages) {
        echo '<script>document.querySelector(".load-more").style.display = "none";</script>';
    }

    wp_die();
}

//GESTION DES FILTRES SUR LA PAGE D'ACCUEIL
function filter_photos() {
    $category = isset($_POST['category']) ? intval($_POST['category']) : '';
    $format = isset($_POST['formats']) ? intval($_POST['formats']) : '';
    $date_order = isset($_POST['date_order']) ? sanitize_text_field($_POST['date_order']) : '';

    $args = array(
        'post_type' => 'photos',
        'posts_per_page' => -1, // Charge toutes les photos correspondant aux filtres
    );

    if ($category) {
        $args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $category,
        );
    }

    if ($format) {
        $args['tax_query'][] = array(
            'taxonomy' => 'formats',
            'field'    => 'term_id',
            'terms'    => $format,
        );
    }

    // Ajout du tri par date
    if ($date_order == "newest") {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    } elseif ($date_order == "oldest") {
        $args['orderby'] = 'date';
        $args['order'] = 'ASC';
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('templates/photo-block'); // Affiche chaque photo
        endwhile;
    else :
        echo '<p>Aucune photo trouvée.</p>';
    endif;

    wp_die(); // Stoppe l'exécution de WordPress proprement
}
add_action('wp_ajax_filter_photos', 'filter_photos');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos'); // Permet aux visiteurs non connectés d'utiliser l'AJAX

//GESTION DE LA LIGHTBOX
function enqueue_lightbox_script() {
    wp_enqueue_script('lightbox-script', get_template_directory_uri() . '/assets/js/lightbox.js', array(), false, true);
}
add_action('wp_enqueue_scripts', 'enqueue_lightbox_script');
