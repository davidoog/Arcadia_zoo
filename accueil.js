document.querySelector('.button-arrow').addEventListener('click', function() {
    const tigreSection = document.getElementById('tigre-accueil');
    
    // Obtenir la position de l'image du tigre et ajuster le défilement
    const offset = 70; // Ajuste cette valeur selon tes besoins (100px avant l'image du tigre)
    const targetPosition = tigreSection.getBoundingClientRect().top + window.pageYOffset - offset;

    // Faire défiler avec un offset
    window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'  // Défilement fluide
    });
});

// Ouvre/ferme le menu latéral en cliquant sur le menu hamburger
document.getElementById('menu-icon').addEventListener('click', function(e) {
    e.stopPropagation(); // Empêche la propagation du clic à d'autres éléments
    const sideMenu = document.getElementById('side-menu');
    
    // Toggle pour afficher ou cacher le menu
    if (sideMenu.classList.contains('open')) {
        sideMenu.classList.remove('open');
        sideMenu.classList.add('closing'); // Ajouter l'animation pour fermer vers la gauche
    } else {
        sideMenu.classList.remove('closing');
        sideMenu.classList.add('open'); // Ajouter l'animation pour ouvrir depuis la gauche
    }
});

// Ferme le menu si on clique en dehors du menu ou du menu icon
document.addEventListener('click', function(event) {
    const sideMenu = document.getElementById('side-menu');
    const menuIcon = document.getElementById('menu-icon');

    // Vérifie si l'endroit cliqué est en dehors du menu ou du bouton
    if (!sideMenu.contains(event.target) && !menuIcon.contains(event.target)) {
        if (sideMenu.classList.contains('open')) {
            sideMenu.classList.remove('open'); // Ferme le menu
            sideMenu.classList.add('closing'); // Animer pour fermeture vers la gauche
        }
    }
});