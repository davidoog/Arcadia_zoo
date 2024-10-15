// Reset des visites d'animaux 
const resetButton = document.getElementById('resetButton');
if (resetButton) {
    resetButton.addEventListener('click', function () {
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
}

// Changement de l'heure du parc 
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
})