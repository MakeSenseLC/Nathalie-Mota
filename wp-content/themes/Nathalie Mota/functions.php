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
// Modification de la fonction de filtrage pour inclure la pagination
function filter_photos() {
    $category = isset($_POST['category']) ? intval($_POST['category']) : '';
    $format = isset($_POST['formats']) ? intval($_POST['formats']) : '';
    $date_order = isset($_POST['date_order']) ? sanitize_text_field($_POST['date_order']) : '';
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = 8;

    $args = array(
        'post_type' => 'photos',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged
    );

    // Ajout des filtres taxonomiques
    $tax_query = array();
    
    if ($category) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $category,
        );
    }

    if ($format) {
        $tax_query[] = array(
            'taxonomy' => 'formats',
            'field'    => 'term_id',
            'terms'    => $format,
        );
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    // Tri par date
    if ($date_order == "newest") {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    } elseif ($date_order == "oldest") {
        $args['orderby'] = 'date';
        $args['order'] = 'ASC';
    }

    $query = new WP_Query($args);
    $total_posts = $query->found_posts;
    $max_pages = ceil($total_posts / $posts_per_page);

    $response = array(
        'html' => '',
        'max_pages' => $max_pages,
        'current_page' => $paged
    );

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('templates/photo-block');
        }
        $response['html'] = ob_get_clean();
    } else {
        $response['html'] = '<p>Aucune photo trouvée.</p>';
    }

    wp_reset_postdata();
    wp_send_json($response);
    wp_die();
}
add_action('wp_ajax_filter_photos', 'filter_photos');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos'); // Permet aux visiteurs non connectés d'utiliser l'AJAX

//GESTION DE LA LIGHTBOX
function enqueue_lightbox_script() {
    wp_enqueue_script('lightbox-script', get_template_directory_uri() . '/assets/js/lightbox.js', array(), false, true);
    
    // Ajouter la localisation des variables AJAX juste après l'enregistrement du script
    wp_localize_script('lightbox-script', 'ajax_loadmore', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_lightbox_script');

function get_category_photos() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    
    $args = array(
        'post_type' => 'photos',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'name',
                'terms'    => $category
            )
        )
    );

    $query = new WP_Query($args);
    $photos = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $photos[] = array(
                'src' => get_the_post_thumbnail_url(),
                'ref' => get_field('reference'),
                'category' => $category,
                'postId' => get_the_ID()
            );
        }
    }

    wp_reset_postdata();
    wp_send_json(array('photos' => $photos));
    wp_die();
}

add_action('wp_ajax_get_category_photos', 'get_category_photos');
add_action('wp_ajax_nopriv_get_category_photos', 'get_category_photos');
