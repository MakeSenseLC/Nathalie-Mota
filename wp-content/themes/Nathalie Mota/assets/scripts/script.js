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

////////////////////////////////////
//NAVIGATION MINIATURE SINGLE-PAGE//
////////////////////////////////////
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
});

////////////////////////
//GESTION DU LOAD MORE//
////////////////////////
document.addEventListener('DOMContentLoaded', function () {
    const loadMoreButton = document.querySelector('.load-more');
    const photoList = document.querySelector('.photo-list');
    let currentPage = 1;
    let currentFilters = {
        category: '',
        formats: '',
        date_order: ''
    };

    // Fonction pour charger les photos
    function loadPhotos(page = 1, replace = false) {
        const formData = new FormData();
        formData.append('action', 'filter_photos');
        formData.append('page', page);
        formData.append('category', currentFilters.category);
        formData.append('formats', currentFilters.formats);
        formData.append('date_order', currentFilters.date_order);

        fetch(ajax_loadmore.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (replace) {
                photoList.innerHTML = data.html;
            } else {
                photoList.insertAdjacentHTML('beforeend', data.html);
            }

            loadMoreButton.style.display = 
                data.current_page >= data.max_pages ? 'none' : 'block';
        })
        .catch(error => console.error('Erreur AJAX:', error));
    }

    // Gestionnaire pour le bouton "Charger plus"
    loadMoreButton?.addEventListener('click', function() {
        currentPage++;
        loadPhotos(currentPage, false);
    });

///////////////////////
//GESTION DES FILTRES//
///////////////////////
const dropdowns = document.querySelectorAll(".dropdown");
    dropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector(".dropdown-btn");
        const menu = dropdown.querySelector(".dropdown-menu");
        const input = dropdown.querySelector("input");
        const defaultText = btn.textContent.replace('❯', '').trim();

        btn.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropdowns.forEach(d => {
                if (d !== dropdown) d.classList.remove("active");
            });
            dropdown.classList.toggle("active");
        });

        menu.querySelectorAll("li").forEach(item => {
            item.addEventListener("click", function(e) {
                e.preventDefault();
                const value = this.getAttribute("data-value");

                if (value === "") {
                    btn.innerHTML = defaultText + ' <span class="chevron">&#10095;</span>';
                    input.value = "";
                } else {
                    btn.innerHTML = this.textContent + ' <span class="chevron">&#10095;</span>';
                    input.value = value;
                }

                dropdown.classList.remove("active");
                menu.querySelectorAll("li").forEach(li => li.classList.remove("selected"));
                this.classList.add("selected");

                // Mise à jour des filtres actuels
                currentFilters = {
                    category: document.getElementById("category-value").value,
                    formats: document.getElementById("format-value").value,
                    date_order: document.getElementById("date-value").value
                };

                // Réinitialiser la pagination et charger les nouvelles photos
                currentPage = 1;
                loadPhotos(1, true);
            });
        });
    });

    // Fermer les dropdowns au clic extérieur
    document.addEventListener("click", function(event) {
        if (!event.target.closest(".dropdown")) {
            dropdowns.forEach(dropdown => dropdown.classList.remove("active"));
        }
    });
});

//////////////////////////
//GESTION DE LA LIGHTBOX//
//////////////////////////
document.addEventListener("DOMContentLoaded", function () {
    let photos = [];
    let currentIndex = 0;
    const isHomePage = document.body.classList.contains('home');

    const lightbox = document.querySelector(".lightbox");
    const lbCurrentPhoto = document.querySelector(".lbCurrentPhoto");
    const lbRef = document.querySelector(".lightbox .overlay-ref");
    const lbCategory = document.querySelector(".lightbox .overlay-category");
    const closeBtn = document.querySelector(".cross");
    const prevBtn = document.querySelector(".lbPrevious");
    const nextBtn = document.querySelector(".lbNext");
    const photoList = document.querySelector(".photo-list");

    function initializeLightbox() {
        photos = [];
        const photoBlocks = document.querySelectorAll(".photo-block");

        photoBlocks.forEach((block, index) => {
            const img = block.querySelector("img");
            const ref = block.querySelector(".overlay-ref").innerText;
            const category = block.querySelector(".overlay-category").innerText;
            const fullScreenIcon = block.querySelector(".full-screen");

            photos.push({ 
                src: img.src, 
                ref, 
                category
            });

            fullScreenIcon.removeEventListener("click", openLightbox);
            fullScreenIcon.addEventListener("click", (e) => openLightbox(e, index));
        });
    }

    async function loadCategoryPhotos(category, clickedRef) {
        const formData = new FormData();
        formData.append('action', 'get_category_photos');
        formData.append('category', category);

        try {
            const response = await fetch(ajax_loadmore.ajax_url, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            photos = data.photos;
            
            // Trouve l'index de la photo cliquée dans la nouvelle liste
            currentIndex = photos.findIndex(photo => photo.ref === clickedRef);
            
            // Si par hasard on ne trouve pas la photo (ne devrait pas arriver), on prend l'index 0
            if (currentIndex === -1) currentIndex = 0;
            
            updateLightbox();
        } catch (error) {
            console.error('Erreur lors du chargement des photos:', error);
        }
    }

    async function openLightbox(event, index) {
        event.preventDefault();
        event.stopPropagation();
        
        // Stocke les informations de la photo cliquée
        const clickedPhoto = photos[index];
        currentIndex = index;
        
        // Affiche d'abord la photo cliquée
        updateLightbox();
        lightbox.classList.add("active");
        
        // Si on est sur une single-photo, charge ensuite toutes les photos de la catégorie
        if (!isHomePage) {
            await loadCategoryPhotos(clickedPhoto.category.trim(), clickedPhoto.ref);
        }
    }

    function updateLightbox() {
        if (photos[currentIndex]) {
            lbCurrentPhoto.src = photos[currentIndex].src;
            lbRef.innerText = photos[currentIndex].ref;
            lbCategory.innerText = photos[currentIndex].category;
        }
    }

    closeBtn.addEventListener("click", () => {
        lightbox.classList.remove("active");
    });

    prevBtn.addEventListener("click", () => {
        currentIndex = (currentIndex - 1 + photos.length) % photos.length;
        updateLightbox();
    });

    nextBtn.addEventListener("click", () => {
        currentIndex = (currentIndex + 1) % photos.length;
        updateLightbox();
    });

    // Initialisation au chargement de la page
    initializeLightbox();

    // Gestion des mises à jour AJAX sur la home
    if (isHomePage) {
        const loadMoreBtn = document.querySelector(".load-more");
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener("click", () => {
                setTimeout(initializeLightbox, 1000);
            });
        }

        const observer = new MutationObserver(() => {
            initializeLightbox();
        });

        observer.observe(photoList, { childList: true, subtree: true });
    }
});

///////////////////////
//GESTION MENU BURGER//
///////////////////////
document.addEventListener('DOMContentLoaded', function () {
    const burger = document.querySelector('.burger');
    const closeMenu = document.querySelector('.close-menu');
    const mobileMenu = document.querySelector('.menu-mobile');
    const body = document.body;

    function toggleMenu() {
        mobileMenu.classList.toggle('active');
        body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : 'auto';
    }

    burger.addEventListener('click', toggleMenu);
    closeMenu.addEventListener('click', toggleMenu);

    // Ferme le menu si un lien est cliqué
    document.querySelectorAll('.menu-mobile-links a').forEach(link => {
        link.addEventListener('click', toggleMenu);
    });
});
