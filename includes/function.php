<?php
// Fonctions utilitaires ici.
require_once(plugin_dir_path(__FILE__) . 'display-images.php');
function custom_image_uploader_enqueue_styles() {
    wp_enqueue_style('custom-image-uploader-styles', plugin_dir_url(dirname(__FILE__)) . 'assets/css/style.css');
}
add_action('wp_enqueue_scripts', 'custom_image_uploader_enqueue_styles');

function custom_image_uploader_enqueue_scripts() {
    wp_enqueue_script('custom-image-uploader-scripts', plugin_dir_url(dirname(__FILE__)) . 'assets/js/scripts.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'custom_image_uploader_enqueue_scripts');

function enqueue_custom_scripts() {
    wp_enqueue_script('custom-pagination', plugin_dir_url(__FILE__) . 'path/to/your/javascript.js', array('jquery'), null, true);

    // Ajoutez des variables à votre script
    wp_localize_script('custom-pagination', 'frontendajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');