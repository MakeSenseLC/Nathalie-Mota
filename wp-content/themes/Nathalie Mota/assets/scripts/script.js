////////////////////////
//GESTION DE LA MODALE//
////////////////////////
document.addEventListener('DOMContentLoaded', function() {
    const modalLinks = document.querySelectorAll('.modal');
    const overlay = document.querySelector('.overlay');
    const modalContact = document.querySelector('.modal-contact');

    // Ouvrir la modal
    modalLinks.forEach(function(modalLink) {
        modalLink.addEventListener('click', function(e) {
            e.preventDefault(); // Pour éviter le comportement par défaut du lien
            overlay.style.display = 'block';
        });
    });

    // Fermer la modal lors du clic sur l'overlay
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.style.display = 'none';
        }
    });

    // Fermer avec la touche Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.style.display === 'block') {
            overlay.style.display = 'none';
        }
    });
});

// Ajouter la valeur du champs référence dans la modale
jQuery(document).ready(function($) {
    // Quand un lien avec la classe .modal est cliqué
    $('.modal').on('click', function(e) {
        e.preventDefault(); // Empêche le comportement par défaut du lien

        // Assurez-vous que la variable photoReference est définie
        if (typeof photoReference !== 'undefined' && photoReference !== '') {
            // Remplissez le champ du formulaire avec la valeur de la référence
            $('.ref-photo').val(photoReference);
        }
    });
});

// Changer la miniature au survol de la fleche gauche
document.addEventListener('DOMContentLoaded', function() {
    const previewImage = document.getElementById('preview-image');
    const prevArrow = document.querySelector('.prev-arrow');
    const nextArrow = document.querySelector('.next-arrow');
    
    // Stockage des URLs originales
    const nextImageUrl = previewImage.getAttribute('data-next');
    const prevImageUrl = previewImage.getAttribute('data-prev');
    
    // Gestion du survol de la flèche gauche
    prevArrow.addEventListener('mouseenter', function() {
        previewImage.src = prevImageUrl;
    });
    
    prevArrow.addEventListener('mouseleave', function() {
        previewImage.src = nextImageUrl;
    });
    
    // Gestion du survol de la flèche droite
    nextArrow.addEventListener('mouseenter', function() {
        previewImage.src = nextImageUrl;
    });
    
    nextArrow.addEventListener('mouseleave', function() {
        previewImage.src = nextImageUrl;
    });
});

////////////////////////
//GESTION DU LOAD MORE//
////////////////////////
document.addEventListener('DOMContentLoaded', function () {
    const loadMoreButton = document.querySelector('.load-more');
    let currentPage = 1;

    loadMoreButton.addEventListener('click', function () {
        currentPage++;

        fetch(ajax_loadmore.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            },
            body: new URLSearchParams({
                action: 'load_more_photos',
                page: currentPage,
            }),
        })
            .then((response) => response.text())
            .then((data) => {
                const trimmedData = data.trim();

                if (trimmedData) {
                    document.querySelector('.photo-list').insertAdjacentHTML('beforeend', trimmedData);
                }

                // Vérifie si la réponse contient le script cachant le bouton
                if (!trimmedData || trimmedData.includes('<script>')) {
                    loadMoreButton.style.display = 'none';
                }
            })
            .catch((error) => console.error('Erreur AJAX:', error));
    });
});

///////////////////////
//GESTION DES FILTRES//
///////////////////////
document.addEventListener("DOMContentLoaded", function () {
    const categoryFilter = document.getElementById("category-filter");
    const formatFilter = document.getElementById("format-filter");
    const dateFilter = document.getElementById("date-filter");
    const photoList = document.querySelector(".photo-list");

    function fetchFilteredPhotos() {
        const category = categoryFilter.value;
        const format = formatFilter.value;
        const dateOrder = dateFilter.value;

        const formData = new FormData();
        formData.append("action", "filter_photos");
        formData.append("category", category);
        formData.append("formats", format);
        formData.append("date_order", dateOrder);

        fetch(ajax_loadmore.ajax_url, {
            method: "POST",
            body: formData,
        })        
        .then(response => response.text())
        .then(data => {
            photoList.innerHTML = data; // Met à jour la liste des photos
        })
        .catch(error => console.error("Erreur AJAX :", error));
    }

    categoryFilter.addEventListener("change", fetchFilteredPhotos);
    formatFilter.addEventListener("change", fetchFilteredPhotos);
    dateFilter.addEventListener("change", fetchFilteredPhotos);
});
