<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    
    <?php wp_body_open(); ?>

    <header class="header">
        <?php the_custom_logo(); ?>
        <?php wp_nav_menu( array( 'menu' => 'Principal','theme_location' => 'menu-1' ) ); ?>
    </header>