<?php
// index.php
session_start();

require_once("controllers/AuthController.php");
require_once("controllers/PatientController.php");
require_once("controllers/DoctorController.php");

if(empty($_GET["page"])){
    $page = "home";
} else {
    $path = explode("/", filter_var($_GET["page"], FILTER_SANITIZE_URL));
    $page = $path[0];
}

$authController = new AuthController();
$patientController = new PatientController();
$doctorController = new DoctorController();

switch($page){
    case "home":
        require_once("views/pages/home.php");
        break;
        
    case "auth":
        $action = $path[1] ?? 'login';
        switch($action){
            case "login":
                $authController->login();
                break;
            case "register":
                $authController->register();
                break;
            case "logout":
                $authController->logout();
                break;
            default:
                echo "<p>Page d'authentification non trouvée</p>";
                break;
        }
        break;
        
    case "patient":
        if(!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            break;
        }
        
        $action = $path[1] ?? 'doctors';
        switch($action){
            case "dashboard":
                $patientController->dashboard();
                break;
            case "doctors":
                $patientController->doctors();
                break;
            case "appointments":
                $patientController->appointments();
                break;
            case "request-appointment":
                $patientController->requestAppointment();
                break;
            case "cancel-appointment":
                $id_rendez_vous = $path[2] ?? null;
                if($id_rendez_vous){
                    $patientController->cancelAppointment($id_rendez_vous);
                } else {
                    header('Location: /patient/appointments');
                }
                break;
            default:
                echo "<p>Page patient non trouvée</p>";
                break;
        }
        break;
        
    case "doctor":
        if(!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            break;
        }
        
        $action = $path[1] ?? 'patients';
        switch($action){
            case "dashboard":
                $doctorController->dashboard();
                break;
            case "patients":
                $doctorController->patients();
                break;
            case "appointments":
                $doctorController->appointments();
                break;
            case "respond-appointment":
                $doctorController->respondToAppointment();
                break;
            default:
                echo "<p>Page docteur non trouvée</p>";
                break;
        }
        break;
        
    default:
        echo "<p>Page non trouvée</p>";
        break;
}
?>