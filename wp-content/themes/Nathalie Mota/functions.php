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

//Chargement du fichier CSS
function enqueue_custom_css() {
    wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_css' );
function custom_theme_setup() {
    add_theme_support( 'custom-logo' );
}
add_action( 'after_setup_theme', 'custom_theme_setup' );

// Chargement du fichier JS
function enqueue_scripts() {
    wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/scripts/script.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );