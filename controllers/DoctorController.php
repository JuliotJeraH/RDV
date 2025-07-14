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
            return;
        }

        if ($_SESSION['user_role'] != 'medecin') {
            header('Location: home');
            return;
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $doctor = $this->doctorModel->getDoctorByUserId($_SESSION['user_id']);

        require_once '../views/pages/doctor/dashboard.php';
    }

    public function patients() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth/login');
            return;
        }

        if ($_SESSION['user_role'] != 'medecin') {
            header('Location: home');
            return;
        }

        $doctor = $this->doctorModel->getDoctorByUserId($_SESSION['user_id']);
        $pendingAppointments = $this->doctorModel->getPendingAppointments($doctor['id_medecin']);

        // Gestion de la recherche
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        if (!empty($search)) {
            $patients = $this->doctorModel->searchPatients($search, $doctor['id_medecin']);
        } else {
            // Par défaut, afficher les patients avec des rendez-vous en attente
            $patients = [];
            foreach ($pendingAppointments as $appointment) {
                $patients[] = [
                    'id_patient' => $appointment['id_patient'],
                    'nom' => $appointment['patient_nom']
                ];
            }
            // Supprimer les doublons
            $patients = array_unique($patients, SORT_REGULAR);
        }

        require_once '../views/pages/doctor/patients.php';
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

        require_once '../views/pages/doctor/appointments.php';
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
                header('Location: doctor/patients');
            } else {
                $error = "Erreur lors du traitement de la demande";
                $doctor = $this->doctorModel->getDoctorByUserId($_SESSION['user_id']);
                $pendingAppointments = $this->doctorModel->getPendingAppointments($doctor['id_medecin']);
                require_once '../views/pages/doctor/patients.php';
            }
        } else {
            header('Location: doctor/patients');
        }
    }
}
?>