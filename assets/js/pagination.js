jQuery(document).ready(function($) {
    $('body').on('click', '.page-numbers', function(e) {
        e.preventDefault();

        var page = $(this).attr('href').split('paged=')[1];

        $.ajax({
            url: frontendajax.ajaxurl,
            type: 'post',
            data: {
                action: 'load_more_images',
                page: page
            },
            success: function(response) {
                // Remplacez le contenu actuel des images par le nouveau contenu
                $('#your-images-container').html(response);
            }
        });
    });
});
