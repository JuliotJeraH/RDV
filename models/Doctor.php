<?php
// models/Doctor.php
class Doctor {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createDoctor($id_utilisateur, $nom, $specialite) {
        $conn = $this->db->connect();
        $query = 'INSERT INTO Medecins (id_utilisateur, nom, specialite) 
                  VALUES (:id_utilisateur, :nom, :specialite)';
        $stmt = $conn->prepare($query);
        
        $stmt->bindParam(':id_utilisateur', $id_utilisateur);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':specialite', $specialite);
        
        return $stmt->execute();
    }

    public function getDoctorByUserId($id_utilisateur) {
        $conn = $this->db->connect();
        $query = 'SELECT * FROM Medecins WHERE id_utilisateur = :id_utilisateur';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPatientById($id_patient) {
        $conn = $this->db->connect();
        $query = 'SELECT * FROM Patients WHERE id_patient = :id_patient';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_patient', $id_patient);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPendingAppointments($id_medecin) {
        $conn = $this->db->connect();
        $query = 'SELECT r.*, p.nom as patient_nom 
                  FROM Rendez_vous r 
                  JOIN Patients p ON r.id_patient = p.id_patient 
                  WHERE r.id_medecin = :id_medecin AND r.statut = "en_attente" 
                  ORDER BY r.date_demande DESC';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_medecin', $id_medecin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPatientAppointments($id_patient) {
        $conn = $this->db->connect();
        $query = 'SELECT r.*, m.nom as medecin_nom, m.specialite 
                  FROM Rendez_vous r 
                  JOIN Medecins m ON r.id_medecin = m.id_medecin 
                  WHERE r.id_patient = :id_patient 
                  AND r.statut = "accepte"
                  AND (r.date_rendez_vous >= CURDATE() OR r.date_rendez_vous IS NULL)
                  ORDER BY r.date_rendez_vous ASC';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_patient', $id_patient);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function respondToAppointment($id_rendez_vous, $response, $date_rendez_vous = null) {
        $conn = $this->db->connect();
        
        if ($response === 'accepte' && $date_rendez_vous) {
            $query = 'UPDATE Rendez_vous SET statut = :statut, date_rendez_vous = :date_rendez_vous 
                      WHERE id_rendez_vous = :id_rendez_vous';
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':statut', $response);
            $stmt->bindParam(':date_rendez_vous', $date_rendez_vous);
            $stmt->bindParam(':id_rendez_vous', $id_rendez_vous);
        } else {
            $query = 'UPDATE Rendez_vous SET statut = :statut WHERE id_rendez_vous = :id_rendez_vous';
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':statut', $response);
            $stmt->bindParam(':id_rendez_vous', $id_rendez_vous);
        }
        
        return $stmt->execute();
    }

    public function searchPatients($search, $id_medecin) {
        $conn = $this->db->connect();
        $query = 'SELECT DISTINCT p.* 
                  FROM Patients p 
                  JOIN Rendez_vous r ON p.id_patient = r.id_patient 
                  WHERE r.id_medecin = :id_medecin 
                  AND (p.nom LIKE :search OR p.groupe_sanguin LIKE :search)';
        $stmt = $conn->prepare($query);
        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':search', $searchTerm);
        $stmt->bindParam(':id_medecin', $id_medecin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAcceptedAppointments($id_medecin) {
        $conn = $this->db->connect();
        $query = 'SELECT r.*, p.nom as patient_nom 
                  FROM Rendez_vous r 
                  JOIN Patients p ON r.id_patient = p.id_patient 
                  WHERE r.id_medecin = :id_medecin 
                  AND r.statut = "accepte"
                  AND (r.date_rendez_vous >= NOW() OR r.date_rendez_vous IS NULL)
                  ORDER BY r.date_rendez_vous ASC';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_medecin', $id_medecin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>