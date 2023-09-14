<?php
function custom_image_upload_form() {
    if (current_user_can('manage_options')) {
        ob_start();

        // Formulaire
        ?>
        <form action="" method="post" enctype="multipart/form-data">
    		<input type="file" name="my_image_upload" id="my_image_upload" />
    		<textarea name="image_description" id="image_description" placeholder="Ajoutez une description..."></textarea>
    		<input type="submit" name="submit_image_upload" value="Envoyer l'image" />
    		<?php wp_nonce_field('custom_image_upload', 'custom_image_nonce'); ?>
		</form>

        <?php

        // Afficher les images
        display_uploaded_images_backend();

        return ob_get_clean();
    } else {
        return "Ce shortcode est destiné à être utilisé par un administrateur uniquement.";
    }
}

add_shortcode('custom_image_upload_form', 'custom_image_upload_form');

function upc_display_images_frontend() {
    $args = array(
    	'post_type'      => 'attachment',
    	'post_mime_type' => 'image',
    	'posts_per_page' => 10, // Nombre d'images par page.
    	'paged'          => get_query_var('paged') ? get_query_var('paged') : 1, // Pour la pagination.
    	'post_status'    => 'inherit',
    	'meta_key'       => 'uploaded_by_client',
    	'meta_value'     => 'yes'
	);

    $image_query = new WP_Query($args);
    ob_start();

    echo '<div class="container-images">';  // Début de la div conteneur
	
    if ($image_query->have_posts()) {
        while ($image_query->have_posts()) {
            $image_query->the_post();
            $image_url = wp_get_attachment_url(get_the_ID());
			echo '<div class="image-wrapper">'; // Nouvelle div pour regrouper l'image et sa description
    			echo '<div class="image-item">'; // Début de la div pour chaque image
        			echo '<img src="' . esc_url($image_url) . '" alt="Image Uploadée" class="openModal" data-src="' . esc_url($image_url) . '"/>';
    			echo '</div>'; // Fin de la div pour chaque image
    			echo '<p class="image-description">' . esc_html(get_the_content()) . '</p>'; // Déplacement de la description en dehors de .image-item
			echo '</div>'; // Fin de la nouvelle div
        }
        
		echo '</div>';  // Fin de la div conteneur
        // Ajout de la pagination ici.
        echo paginate_links(array(
            'total' => $image_query->max_num_pages
        ));
        wp_reset_postdata();
    } else {
        echo 'Aucune image uploadée pour le moment.';
    }
	echo '<div id="myModal" class="modal">
    		<span class="close">&times;</span>
    		<img class="modal-content" id="modalImage">
		  </div>';

    return ob_get_clean();
}

add_shortcode('upc_display_images_frontend', 'upc_display_images_frontend');