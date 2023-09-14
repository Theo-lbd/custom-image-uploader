function openModal(id) {
    var modal = document.getElementById('modal-' + id);
    modal.style.display = "block";
}

function closeModal(id) {
    var modal = document.getElementById('modal-' + id);
    modal.style.display = "none";
}

jQuery(document).ready(function($) {
    $(".image-item img").click(function() {
        var imageUrl = $(this).attr("src");
        $("body").append('<div class="modal"><div class="modal-content"><span class="close">&times;</span><img src="' + imageUrl + '"></div></div>');
        
        // Afficher la modale
        $(".modal").fadeIn();

        // Lorsque l'utilisateur clique sur <span> (x), fermez la modale
        $(".close").click(function() {
            $(".modal").fadeOut(function() {
                $(this).remove();
            });
        });
    });
});
