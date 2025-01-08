////////////////////////
//GESTION DE LA MODALE//
////////////////////////
document.addEventListener('DOMContentLoaded', function() {
    const modalLink = document.querySelector('.modal');
    const overlay = document.querySelector('.overlay');
    const modalContact = document.querySelector('.modal-contact');

    // Ouvrir la modal
    modalLink.addEventListener('click', function(e) {
        e.preventDefault(); // Pour éviter le comportement par défaut du lien
        overlay.style.display = 'block';
    });

    // Fermer la modal lors du clic sur l'overlay
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.style.display = 'none';
        }
    });

    // Fermer avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.style.display === 'block') {
            overlay.style.display = 'none';
        }
    });
});
