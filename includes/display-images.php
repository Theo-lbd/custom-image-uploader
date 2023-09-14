<?php
function display_uploaded_images_backend() {
    // Récupère toutes les images téléchargées pour ce plugin
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;  // récupère la page courante

	$args = array(
    	'post_type' => 'attachment',
    	'post_status' => 'inherit',
    	'post_mime_type' => 'image',
    	'orderby' => 'date',
    	'order' => 'DESC',
    	'posts_per_page' => 10,  // nombre d'images par page
    	'paged' => $paged,
    	'meta_query' => array(
        	array(
            	'key' => 'uploaded_by_client',
            	'value' => 'yes'
        	)
    	)
	);

    $query_images = new WP_Query($args);
    $images = $query_images->posts;

    // Début du formulaire
echo '<form method="post" action="">';

    // Bouton Supprimer pour toutes les images sélectionnées
    echo wp_nonce_field('bulk_delete_image_action', 'bulk_delete_image_nonce', true, false);
    echo '<input type="submit" name="bulk_delete_images" value="Supprimer les images sélectionnées">';
	
// Affiche les images
echo '<div class="uploaded-images">';
if (!empty($images)) {
    foreach ($images as $image) {
        $image_url = wp_get_attachment_url($image->ID);

        // Ajout de la div avec la classe "image-block" ici
        echo '<div class="image-block">';
		echo '<div class="checkbox-container">';
    		echo '<input type="checkbox" name="images_to_delete[]" value="' . esc_attr($image->ID) . '">';
    		echo ' Sélectionner';
		echo '</div>';

        echo '<div class="image-wrapper">';
        	echo '<img src="' . esc_url($image_url) . '" alt="Image Uploadée" style="max-width:200px; display:block; margin-top:10px;" />';
		echo '</div>';
		echo '<p>' . esc_html($image->post_content) . '</p>';
		
        // Formulaire de modification caché
		echo '<div class="modal" id="modal-' . $image->ID . '">
        		<div class="modal-content">
            		<button class="close-button" onclick="closeModal(' . $image->ID . ')">&times;</button>
            		<form method="post" action="">
                		<textarea name="edited_description">' . esc_html($image->post_content) . '</textarea>
                		<input type="hidden" name="image_id_to_edit" value="' . esc_attr($image->ID) . '">
                		<input type="submit" name="edit_description" value="Mettre à jour">
            		</form>
        		</div>
    		</div>';
		
	echo '<div class="button-group">';
		echo '<button type="button" class="edit-description-button" data-id="' . $image->ID . '" onclick="openModal(' . $image->ID . ')">Modifier</button>';

		
        // Bouton Supprimer pour une image individuelle
        echo '<form method="post" action="">
            <input type="hidden" name="image_to_delete_single" value="' . esc_attr($image->ID) . '">
            '. wp_nonce_field('delete_image_single_action', 'delete_image_single_nonce', true, false) .'
            <input type="submit" name="delete_image_single" value="Supprimer">
        </form>';
echo '</div>'; /* fin de .button-group */
        // Fermeture de la div avec la classe "image-block" ici
        echo '</div>';
    }

    // Ajout de la pagination ici
    echo paginate_links(array(
        'total' => $query_images->max_num_pages
    ));
} else {
    echo 'Aucune image uploadée pour le moment.';
}
echo '</div>';

// Fin du formulaire
echo '</form>';

}