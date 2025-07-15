<?php
// controllers/DoctorController.php
require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../models/User.php';

class DoctorController {
    private $doctorModel;
    private $userModel;

    public function __construct() {
        $this->doctorModel = new Doctor();
        $this->userModel = new User();
    }

    public function dashboard() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth/login');
            exit();
        }
    
        if ($_SESSION['user_role'] != 'medecin') {
            header('Location: index.php?page=home');
            exit();
        }
    
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $doctor = $this->doctorModel->getDoctorByUserId($_SESSION['user_id']);
        $appointments = $this->doctorModel->getAcceptedAppointments($doctor['id_medecin']);
    
        // Debug (à retirer en production)
        error_log(print_r($appointments, true));
    
        require_once __DIR__ . '/../views/pages/doctor/dashboard.php';
    }

    public function patients() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth/login');
            return;
        }
    
        if ($_SESSION['user_role'] != 'medecin') {
            header('Location: index.php?page=home');
            return;
        }
    
        $doctor = $this->doctorModel->getDoctorByUserId($_SESSION['user_id']);
        $search = $_GET['search'] ?? '';
    
        if (!empty($search)) {
            $patients = $this->doctorModel->searchPatients($search, $doctor['id_medecin']);
            $pendingAppointments = [];
        } else {
            $pendingAppointments = $this->doctorModel->getPendingAppointments($doctor['id_medecin']);
            
            // Récupérer les informations complètes des patients
            $patients = [];
            foreach ($pendingAppointments as $appointment) {
                $patient = $this->doctorModel->getPatientById($appointment['id_patient']);
                if ($patient) {
                    $patients[] = $patient;
                }
            }
            // Supprimer les doublons
            $patients = array_unique($patients, SORT_REGULAR);
        }
    
        require_once __DIR__ . '/../views/pages/doctor/patients.php';
    }

    public function appointments() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth/login');
            return;
        }

        if ($_SESSION['user_role'] != 'medecin') {
            header('Location: home');
            return;
        }

        $doctor = $this->doctorModel->getDoctorByUserId($_SESSION['user_id']);
        $appointments = $this->doctorModel->getAcceptedAppointments($doctor['id_medecin']);

        require_once __DIR__ . '/../views/pages/doctor/appointments.php';
    }

    public function respondToAppointment() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth/login');
            return;
        }

        if ($_SESSION['user_role'] != 'medecin') {
            header('Location: home');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_rendez_vous = $_POST['id_rendez_vous'];
            $response = $_POST['response'];
            $date_rendez_vous = isset($_POST['date_rendez_vous']) ? $_POST['date_rendez_vous'] : null;

            if ($this->doctorModel->respondToAppointment($id_rendez_vous, $response, $date_rendez_vous)) {
                header('Location: index.php?page=doctor/patients');
            } else {
                $error = "Erreur lors du traitement de la demande";
                $doctor = $this->doctorModel->getDoctorByUserId($_SESSION['user_id']);
                $pendingAppointments = $this->doctorModel->getPendingAppointments($doctor['id_medecin']);
                require_once '../views/pages/doctor/patients.php';
            }
        } else {
            header('Location: index.php?page=doctor/patients');
        }
    }
}
?>