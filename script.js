// Fonction pour afficher les animaux d'un habitat spécifique
const showAnimals = (habitat) => {
    console.log("Clicked on habitat: " + habitat);
    
    // Cacher toutes les sections d'animaux
    document.querySelectorAll('.animal-container').forEach(container => {
        container.classList.add('hidden');
    });

    // Afficher la section correspondante à l'habitat cliqué
    const habitatSection = document.getElementById(`animal-info-${habitat}`);
    
    if (habitatSection) {
        habitatSection.classList.remove('hidden');

        // Faire défiler vers la section des animaux
        habitatSection.scrollIntoView({
            behavior: 'smooth', // Défilement doux
            block: 'start'      // Aligner le conteneur en haut de la page
        });

    } else {
        console.error(`Section d'habitat introuvable pour: animal-info-${habitat}`);
    }
};

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

// Fonction pour afficher les détails d'un animal spécifique
function showAnimalDetails(animal) {
    // Cacher tous les détails d'animaux
    document.querySelectorAll('.animal-details').forEach(function (details) {
        details.classList.add('hidden');
    });

    // Afficher les détails de l'animal cliqué
    const animalDetails = document.getElementById('details-' + animal);
    if (animalDetails) {
        animalDetails.classList.remove('hidden');

        // Calculer le décalage en fonction de la hauteur de la topbar
        const topbarHeight = document.querySelector('.topbar').offsetHeight;
        const elementPosition = animalDetails.getBoundingClientRect().top + window.scrollY;
        const offsetPosition = elementPosition - topbarHeight - 500; // Ajustement de 500px pour l'espacement

        // Effectuer le défilement avec un décalage
        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });

        // Envoi d'une requête pour incrémenter le compteur de visites
        fetch('update_visit.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name: animal }) // Envoi du nom de l'animal
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur lors de la mise à jour du compteur");
            }
            return response.json();
        })
        .then(data => {
            console.log('Compteur de visites mis à jour:', data);
        })
        .catch(error => {
            console.error('Erreur lors de la mise à jour du compteur:', error);
        });

    } else {
        console.error('Détails de l\'animal introuvables pour : ' + animal);
    }
}

document.getElementById('resetButton').addEventListener('click', function () {
    fetch('reset_visits.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message); // Afficher un message de succès

            // Supprimer les phrases de consultation
            const consultationText = document.querySelectorAll('.consultation-info');
            consultationText.forEach(info => info.textContent = ''); // Effacer le texte

        } else {
            alert('Erreur : ' + data.message); // Afficher le message d'erreur
        }
    })
    .catch(error => {
        console.error('Erreur lors de la réinitialisation:', error);
        alert('Erreur lors de la réinitialisation');
    });
});

document.getElementById('updateHoursForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    let openingHour = document.getElementById('opening_hour').value;
    let closingHour = document.getElementById('closing_hour').value;

    fetch('update_hours.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `opening_hour=${openingHour}&closing_hour=${closingHour}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
        } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(error => console.error('Erreur lors de la mise à jour:', error));
});