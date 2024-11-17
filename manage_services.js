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
                                <button class="btn btn-primary" onclick="editService(${service.id})">Modifier</button>
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