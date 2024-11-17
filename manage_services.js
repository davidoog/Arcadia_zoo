window.addEventListener("load", function () {
    function loadServices() {
        fetch("fetch_services.php")
            .then(response => response.json())
            .then(data => {
                const servicesTable = document.getElementById("servicesTable");
                servicesTable.innerHTML = "";  // Réinitialiser le contenu de la table

                // Ajouter chaque service à la table
                data.services.forEach(service => {
                    const serviceRow = `
                        <tr>
                            <td>${service.title}</td>
                            <td>${service.description}</td>
                            <td>
                                <a href="edit_service.php?id=${service.id}" class="btn btn-warning">Modifier</a>
                                <a href="delete_service.php?id=${service.id}" class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    `;
                    servicesTable.innerHTML += serviceRow;  // Ajouter la ligne à la table
                });
            });
    }

    loadServices();  // Charger les services au chargement de la page
});