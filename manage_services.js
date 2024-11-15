document.addEventListener("DOMContentLoaded", function () {
    const serviceForm = document.getElementById("manageServiceForm");
    const serviceIdInput = document.getElementById("serviceId");
    const serviceTitleInput = document.getElementById("serviceTitle");
    const serviceDescriptionInput = document.getElementById("serviceDescription");
    const serviceImageInput = document.getElementById("serviceImage");
    const deleteServiceButton = document.getElementById("deleteService");

    function loadServices() {
        fetch("fetch_services.php")
            .then(response => response.json())
            .then(data => {
                const servicesTable = document.getElementById("servicesTable");
                servicesTable.innerHTML = "";
                data.services.forEach(service => {
                    const serviceRow = `
                        <tr>
                            <td>${service.title}</td>
                            <td>${service.description}</td>
                            <td><img src="${service.image}" alt="${service.title}" width="100"></td>
                            <td>
                                <button class="btn btn-primary" onclick="editService(${service.id})">Modifier</button>
                                <button class="btn btn-danger" onclick="deleteService(${service.id})">Supprimer</button>
                            </td>
                        </tr>
                    `;
                    servicesTable.innerHTML += serviceRow;
                });
            });
    }

    window.editService = function (id) {
        fetch(`get_service.php?id=${id}`)
            .then(response => response.json())
            .then(service => {
                serviceIdInput.value = service.id;
                serviceTitleInput.value = service.title;
                serviceDescriptionInput.value = service.description;
            });
    };
 
    window.deleteService = function (id) {
        if (confirm("Voulez-vous vraiment supprimer ce service ?")) {
            fetch(`delete_service.php?id=${id}`, {
                method: "DELETE",
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        loadServices();
                    } else {
                        alert("Erreur lors de la suppression.");
                    }
                });
        }
    };

    loadServices();
});