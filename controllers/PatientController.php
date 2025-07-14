<?php
// controllers/PatientController.php
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/User.php';

class PatientController {
    private $patientModel;
    private $userModel;

    public function __construct() {
        $this->patientModel = new Patient();
        $this->userModel = new User();
    }

    public function dashboard() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?page=auth/login');
            return;
        }

        if ($_SESSION['user_role'] != 'patient') {
            header('Location: home');
            return;
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $patient = $this->patientModel->getPatientByUserId($_SESSION['user_id']);

        require_once '../views/pages/patient/dashboard.php';
    }

    public function doctors() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            return;
        }

        if ($_SESSION['user_role'] != 'patient') {
            header('Location: /');
            return;
        }

        $patient = $this->patientModel->getPatientByUserId($_SESSION['user_id']);

        // Gestion de la recherche
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        if (!empty($search)) {
            $doctors = $this->patientModel->searchDoctors($search);
        } else {
            $doctors = $this->patientModel->getAllDoctors();
        }

        require_once '../views/pages/patient/doctors.php';
    }

    public function appointments() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            return;
        }

        if ($_SESSION['user_role'] != 'patient') {
            header('Location: /');
            return;
        }

        $patient = $this->patientModel->getPatientByUserId($_SESSION['user_id']);
        $appointments = $this->patientModel->getPatientAppointments($patient['id_patient']);

        require_once '../views/pages/patient/appointments.php';
    }

    public function requestAppointment() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            return;
        }

        if ($_SESSION['user_role'] != 'patient') {
            header('Location: /');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_medecin = $_POST['id_medecin'];
            $motif = trim($_POST['motif']);
            $patient = $this->patientModel->getPatientByUserId($_SESSION['user_id']);

            if ($this->patientModel->requestAppointment($patient['id_patient'], $id_medecin, $motif)) {
                header('Location: /patient/appointments');
            } else {
                $error = "Erreur lors de la demande de rendez-vous";
                $doctors = $this->patientModel->getAllDoctors();
                require_once '../views/pages/patient/doctors.php';
            }
        } else {
            header('Location: /patient/doctors');
        }
    }

    public function cancelAppointment($id_rendez_vous) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            return;
        }

        if ($_SESSION['user_role'] != 'patient') {
            header('Location: /');
            return;
        }

        if ($this->patientModel->cancelAppointment($id_rendez_vous)) {
            header('Location: /patient/appointments');
        } else {
            $error = "Erreur lors de l'annulation du rendez-vous";
            $patient = $this->patientModel->getPatientByUserId($_SESSION['user_id']);
            $appointments = $this->patientModel->getPatientAppointments($patient['id_patient']);
            require_once '../views/pages/patient/appointments.php';
        }
    }
}
?>