<?php
// models/Appointment.php
class Appointment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function deleteAppointment($id_rendez_vous) {
        $conn = $this->db->connect();
        $query = 'DELETE FROM Rendez_vous WHERE id_rendez_vous = :id_rendez_vous';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_rendez_vous', $id_rendez_vous);
        return $stmt->execute();
    }

    public function cleanOldAppointments() {
        $conn = $this->db->connect();
        // Supprime les rendez-vous acceptés dont la date est passée depuis plus de 24h
        $query = 'DELETE FROM Rendez_vous 
                  WHERE statut = "accepte" 
                  AND date_rendez_vous < DATE_SUB(NOW(), INTERVAL 1 DAY)';
        $stmt = $conn->prepare($query);
        return $stmt->execute();
    }
}
?>