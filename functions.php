<?php
if (!defined('ABSPATH')) {
    exit;
}

// Action qui permet de charger des scripts dans notre thème
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles()
{
    // Chargement du style.css du thème parent Twenty Twenty
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    // Chargement du fichier avec la personnalisation du css
    wp_enqueue_style('theme-style', get_stylesheet_directory_uri() . '/css/theme.css', array(), filemtime(get_stylesheet_directory() . '/css/theme.css'));
}

// Ajout du lien "admin" dans la nav du header lorsque l'utiliateur est connecté
function add_admin_header_menu_item($items, $args)
{
    if (is_user_logged_in() && current_user_can('administrator') && $args->theme_location == 'primary') {
        $items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="' . admin_url() . '" class="menu-link" >Admin</a></li>';
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'add_admin_header_menu_item', 10, 2);
