<?php
// models/Patient.php
class Patient {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createPatient($id_utilisateur, $nom, $date_naissance, $groupe_sanguin = null) {
        $conn = $this->db->connect();
        $query = 'INSERT INTO Patients (id_utilisateur, nom, date_naissance, groupe_sanguin) 
                  VALUES (:id_utilisateur, :nom, :date_naissance, :groupe_sanguin)';
        $stmt = $conn->prepare($query);
        
        $stmt->bindParam(':id_utilisateur', $id_utilisateur);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':groupe_sanguin', $groupe_sanguin);
        
        return $stmt->execute();
    }

    public function getPatientByUserId($id_utilisateur) {
        $conn = $this->db->connect();
        $query = 'SELECT * FROM Patients WHERE id_utilisateur = :id_utilisateur';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllDoctors() {
        $conn = $this->db->connect();
        $query = 'SELECT * FROM Medecins';
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchDoctors($search) {
        $conn = $this->db->connect();
        $query = 'SELECT * FROM Medecins WHERE nom LIKE :search OR specialite LIKE :search';
        $stmt = $conn->prepare($query);
        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function requestAppointment($id_patient, $id_medecin, $motif) {
        $conn = $this->db->connect();
        $query = 'INSERT INTO Rendez_vous (id_patient, id_medecin, motif) 
                  VALUES (:id_patient, :id_medecin, :motif)';
        $stmt = $conn->prepare($query);
        
        $stmt->bindParam(':id_patient', $id_patient);
        $stmt->bindParam(':id_medecin', $id_medecin);
        $stmt->bindParam(':motif', $motif);
        
        return $stmt->execute();
    }

    public function getPatientAppointments($id_patient) {
        $conn = $this->db->connect();
        $query = 'SELECT r.*, m.nom as medecin_nom, m.specialite 
                  FROM Rendez_vous r 
                  JOIN Medecins m ON r.id_medecin = m.id_medecin 
                  WHERE r.id_patient = :id_patient 
                  ORDER BY r.date_demande DESC';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_patient', $id_patient);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cancelAppointment($id_rendez_vous) {
        $conn = $this->db->connect();
        $query = 'UPDATE Rendez_vous SET statut = "annule" WHERE id_rendez_vous = :id_rendez_vous';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_rendez_vous', $id_rendez_vous);
        return $stmt->execute();
    }
}
?>