<?php
function handle_image_upload() {
    if (!isset($_POST['custom_image_nonce']) || !wp_verify_nonce($_POST['custom_image_nonce'], 'custom_image_upload')) {
        return;
    }

    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    $uploadedfile = $_FILES['my_image_upload'];
	$filetype = wp_check_filetype(basename($uploadedfile['name']));
	if (!in_array($filetype['type'], array('image/jpg', 'image/jpeg', 'image/png', 'image/gif'))) {
    	return; // Ou vous pouvez ajouter un message d'erreur si vous le souhaitez.
	}

    $upload_overrides = array('test_form' => false);
    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

    if ($movefile && !isset($movefile['error'])) {
        // Image a été téléchargée.
        $description = sanitize_text_field($_POST['image_description']);
		$attachment = array(
    		'post_mime_type' => $movefile['type'],
    		'post_title' => sanitize_file_name($uploadedfile['name']),
    		'post_content' => $description,
    		'post_status' => 'inherit'
		);

        $attach_id = wp_insert_attachment($attachment, $movefile['file']);
        update_post_meta($attach_id, 'uploaded_by_client', 'yes');        
    } else {
            echo "Une erreur est survenue lors du téléchargement de l'image. Veuillez réessayer.";
    }
}
add_action('init', 'handle_image_upload');

function handle_bulk_image_deletion() {
    // Suppression d'une image unique
    if (isset($_POST['delete_image_single']) && isset($_POST['image_to_delete_single']) && isset($_POST['delete_image_single_nonce']) && wp_verify_nonce($_POST['delete_image_single_nonce'], 'delete_image_single_action')) {
        $image_id = intval($_POST['image_to_delete_single']);
        wp_delete_attachment($image_id, true);
    }

    // Suppression en masse
    if (isset($_POST['bulk_delete_images']) && isset($_POST['images_to_delete']) && is_array($_POST['images_to_delete']) && isset($_POST['bulk_delete_image_nonce']) && wp_verify_nonce($_POST['bulk_delete_image_nonce'], 'bulk_delete_image_action')) {
        $images_ids = array_map('intval', $_POST['images_to_delete']);
        foreach($images_ids as $image_id) {
            wp_delete_attachment($image_id, true);
        }
    }
}
add_action('init', 'handle_bulk_image_deletion');

function handle_image_description_update() {
    if (isset($_POST['edit_description']) && isset($_POST['edited_description']) && isset($_POST['image_id_to_edit'])) {
        $new_description = sanitize_text_field($_POST['edited_description']);
        $image_id = intval($_POST['image_id_to_edit']);

        wp_update_post(array(
            'ID' => $image_id,
            'post_content' => $new_description
        ));
    }
}
add_action('init', 'handle_image_description_update');