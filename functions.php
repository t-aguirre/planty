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


//Fonction qui génère un shortcode qui permettra de relier le formulaire de précommande dans Contact Form 7 avec le groupe de champs ACF "Images-precommande"
function get_acf_image($atts)
{
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);

    // Récupération de l'ID de l'image depuis ACF
    $image_id = get_field($atts['id']);

    if ($image_id) {
        // Récupération de l'URL de l'image en utilisant l'ID
        $image_url = wp_get_attachment_image_url($image_id, 'full');
        // Récupération de l'attribut alt
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

        if ($image_url) {
            return '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '">';
        }
    }
    return "Aucune image n'a été assignée";
}
add_shortcode('acf_image', 'get_acf_image');

//Fonction qui génère un shortcode qui permettra de relier le formulaire de précommande dans Contact Form 7 avec le groupe de champs ACF "Images-precommande"
function get_acf_label($atts)
{
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);

    // Récupération du label de l'image depuis ACF
    $label_id = get_field($atts['id']);

    if ($label_id) {
        return esc_html($label_id);
    }

    return "Aucun label n'a été renseigné";
}
add_shortcode('acf_label', 'get_acf_label');



// Footer: génération d'une url dynamique pour l'affichage de l'image de canettes (id: 117) du footer
function img_footer_background()
{
    $img_url = wp_get_attachment_url(569);
    $is_home_page = is_front_page();
?>
    <style type='text/css'>
        #widget1-img-background {
            background-image: url('<?php echo esc_url($img_url); ?>');
            background-size: contain;
            background-position: top;
            height: 131px;
            width: 100%;
            /* Couleur de fond de l'image dans la page d'accueil*/
            background-color: <?php echo $is_home_page ? '#ffffff' : 'transparent'; ?>;
        }

        @media (max-width: 1439px) {
            #widget1-img-background {
                background-size: cover;
                background-repeat: no-repeat;
            }
        }
    </style>
<?php
}
add_action('wp_head', 'img_footer_background');
