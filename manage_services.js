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
    window.location.href = "edit_service.php?id=" + serviceId;
}

// Fonction pour supprimer un service
function deleteService(serviceId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce service ?')) {
        fetch('delete_service.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: serviceId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Le service a été supprimé.');
                location.reload(); // Recharge la page après la suppression
            } else {
                alert('Erreur lors de la suppression du service.');
            }
        })
        .catch(error => console.error('Erreur:', error));
    }
}