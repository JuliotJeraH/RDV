<?php
session_start();
require 'controllers/AuthController.php';
require 'controllers/PatientController.php';
require 'controllers/RendezVousController.php';

$action = $_GET['action'] ?? 'dashboard'; // Page d'accueil par défaut

// Ajoutez cette route
    

switch ($action) {
    // Authentification
    case 'login':
        (new AuthController())->login();
        break;
    case 'register':
        (new AuthController())->register();
        break;

    case 'dashboard':
        require 'views/pages/dashboard.php';
        break;

    case 'logout':
        session_destroy();
        header('Location: ?action=login');
        break;

    // Patients
    case 'patients':
        (new PatientController())->list();
        break;
    case 'add-patient':
        (new PatientController())->create();
        break;

    case 'edit-patient':
        (new PatientController())->edit($_GET['id'] ?? null);
        break;

    // Rendez-vous
    case 'rendezvous':
        (new RendezVousController())->list();
        break;
    case 'add-rdv':
        (new RendezVousController())->create();
        break;

    // Par défaut -> page login
    default:
        header('Location: ?action=dashboard');
        break;
}
?>