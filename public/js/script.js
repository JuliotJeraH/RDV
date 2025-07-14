// Fonction pour afficher/masquer les champs selon le rôle sélectionné
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des messages flash
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // Confirmation avant suppression/annulation
    const deleteForms = document.querySelectorAll('form[action*="cancel"], form[action*="respond-appointment"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) {
                e.preventDefault();
            }
        });
    });
    
    // Gestion des modaux
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const input = modal.querySelector('input[type="text"], input[type="datetime-local"], textarea');
            if (input) input.focus();
        });
    });
});