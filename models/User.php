<?php
// models/User.php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($email, $password, $role) {
        $conn = $this->db->connect();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $query = 'INSERT INTO Utilisateurs (email, mot_de_passe, role) VALUES (:email, :password, :role)';
        $stmt = $conn->prepare($query);
        
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
        
        return $stmt->execute();
    }

    public function login($email, $password) {
        $conn = $this->db->connect();
        
        $query = 'SELECT * FROM Utilisateurs WHERE email = :email';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $user['mot_de_passe'])) {
                return $user;
            }
        }
        return false;
    }

    public function getUserById($id) {
        $conn = $this->db->connect();
        $query = 'SELECT * FROM Utilisateurs WHERE id_utilisateur = :id';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>