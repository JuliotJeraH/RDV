<?php
// controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/Doctor.php';

class AuthController {
    private $userModel;
    private $patientModel;
    private $doctorModel;

    public function __construct() {
        $this->userModel = new User();
        $this->patientModel = new Patient();
        $this->doctorModel = new Doctor();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $role = $_POST['role'];
            $nom = trim($_POST['nom']);
            $date_naissance = $_POST['date_naissance'];

            // Validation simple
            if (empty($email) || empty($password) || empty($nom) || empty($date_naissance)) {
                $error = "Tous les champs sont obligatoires";
                require_once __DIR__ . '/../views/pages/auth/register.php';

                return;
            }

            // Inscription de l'utilisateur
            if ($this->userModel->register($email, $password, $role)) {
                $user = $this->userModel->login($email, $password);
                
                // Création du profil selon le rôle
                if ($role == 'patient') {
                    $groupe_sanguin = $_POST['groupe_sanguin'] ?? null;
                    $this->patientModel->createPatient($user['id_utilisateur'], $nom, $date_naissance, $groupe_sanguin);
                } else {
                    $specialite = trim($_POST['specialite']);
                    $this->doctorModel->createDoctor($user['id_utilisateur'], $nom, $specialite);
                }

                // Connexion automatique après inscription
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_email'] = $user['email'];

                // Redirection selon le rôle
                if ($user['role'] == 'patient') {
                    header('Location: ../views/pages/auth/register.php');
                } else {
                    header('Location: index.php?page=doctor/patients');
                }
            } else {
                $error = "Une erreur s'est produite lors de l'inscription";
                require_once '../views/pages/auth/register.php';
            }
        } else {
            require_once __DIR__ . '/../views/pages/auth/register.php';
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $user = $this->userModel->login($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_email'] = $user['email'];

                // Redirection selon le rôle
                if ($user['role'] == 'patient') {
                    header('Location: index.php?page=patient/dashboard');
                } else {
                    header('Location: index.php?page=doctor/dashboard');
                }
            } else {
                $error = "Email ou mot de passe incorrect";
                require_once __DIR__ . '/../views/pages/auth/login.php';
            }
        } else {
            require_once __DIR__ . '/../views/pages/auth/login.php';

        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: home');
    }
}
?>