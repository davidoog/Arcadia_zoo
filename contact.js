// contact.js

document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour valider l'email
    function validateEmail(email) {
        // Expression régulière pour valider l'email
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Gestion du menu hamburger
    const menuIcon = document.getElementById('menu-icon');
    if (menuIcon) {
        menuIcon.addEventListener('click', function(e) {
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
    } else {
        console.error("Élément avec l'ID 'menu-icon' introuvable.");
    }

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

    // Validation du formulaire de contact
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            const subject = document.getElementById('subject').value.trim();
            const description = document.getElementById('description').value.trim();
            const email = document.getElementById('email').value.trim();

            if (subject === '' || description === '' || email === '') {
                alert('Veuillez remplir tous les champs.');
                event.preventDefault(); // Empêche l'envoi du formulaire si validation échoue
            } else if (!validateEmail(email)) {
                alert('Veuillez entrer une adresse email valide.');
                event.preventDefault(); // Empêche l'envoi du formulaire si email invalide
            } else {
                console.log("Formulaire prêt à être envoyé"); // Vérifier l'état
            }
        });
    } else {
        console.error("Formulaire de contact introuvable.");
    }
});