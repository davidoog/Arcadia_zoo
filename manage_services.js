document.addEventListener("DOMContentLoaded", function () {
    const servicesTable = document.getElementById("servicesTable");

    if (servicesTable) {
        servicesTable.innerHTML = "";  // Réinitialiser le contenu de la table

        fetch("fetch_services.php")
            .then(response => response.json())
            .then(data => {
                data.services.forEach(service => {
                    const serviceRow = `
                        <tr>
                            <td>${service.title}</td>
                            <td>${service.description}</td>
                            <td>
                                <button class="btn btn-warning" onclick="editService(${service.id})">Modifier</button>
                                <button class="btn btn-danger" onclick="deleteService(${service.id})">Supprimer</button>
                            </td>
                        </tr>
                    `;
                    servicesTable.innerHTML += serviceRow;
                });
            })
            .catch(error => console.error('Error loading services:', error));
    } else {
        console.error("L'élément avec l'ID 'servicesTable' est introuvable.");
    }
});

// Fonction pour modifier un service
function editService(serviceId) {
    // Redirige vers la page d'édition du service
    window.location.href = "edit_service.php?id=" + serviceId;
}

// Fonction pour supprimer un service
function deleteService(serviceId) {
    console.log("Tentative de suppression du service avec ID:", serviceId);

    // Vérifier que l'utilisateur confirme la suppression
    if (confirm('Êtes-vous sûr de vouloir supprimer ce service ?')) {
        // Créez une requête AJAX pour envoyer la suppression du service
        fetch('delete_service.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: serviceId }) // Envoi de l'ID en JSON
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Ajoutez ce log pour voir la réponse du serveur
            if (data.status === 'success') {
                alert('Le service a été supprimé.');
                location.reload(); // Recharge la page après la suppression
            } else {
                alert('Erreur lors de la suppression du service : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    }
}