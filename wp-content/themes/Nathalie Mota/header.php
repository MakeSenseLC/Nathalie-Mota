<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    
    <?php wp_body_open(); ?>

    <header >
        <div class="header">
            <?php the_custom_logo(); ?>
            <?php wp_nav_menu( array( 'menu' => 'Principal','theme_location' => 'menu-1' ) ); ?>
            <span class="burger">&#119064;</span>
        </div>
        <div class="menu-mobile">
            <div class="menu-mobile-top">
                <?php the_custom_logo(); ?>
                <span class="close-menu">&#9587;</span>
            </div>
            <div class="menu-mobile-links">
                <?php wp_nav_menu( array( 'menu' => 'Principal','theme_location' => 'menu-1' ) ); ?>
            </div>
            </div>
    </header>