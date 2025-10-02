<?php

class Consultation {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Create new consultation
    public function createConsultation($data) {
        $this->db->query("INSERT INTO consultations (lawyer_id, client_id, question, status) VALUES (:lawyer_id, :client_id, :question, :status)");
        
        $this->db->bind(':lawyer_id', $data['lawyer_id']);
        $this->db->bind(':client_id', $data['client_id']);
        $this->db->bind(':question', $data['question']);
        $this->db->bind(':status', $data['status'] ?? 'pending');

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Answer consultation
    public function answerConsultation($id, $answer) {
        $this->db->query("UPDATE consultations SET answer = :answer, status = 'answered' WHERE id = :id");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':answer', $answer);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get consultations by client ID
    public function getConsultationsByClient($client_id) {
        $this->db->query("SELECT c.*, u.name as lawyer_name FROM consultations c 
                         LEFT JOIN users u ON c.lawyer_id = u.id 
                         WHERE c.client_id = :client_id 
                         ORDER BY c.created_at DESC");
        
        $this->db->bind(':client_id', $client_id);
        
        return $this->db->resultSet();
    }

    // Get consultations by lawyer ID
    public function getConsultationsByLawyer($lawyer_id) {
        $this->db->query("SELECT c.*, u.name as client_name FROM consultations c 
                         LEFT JOIN users u ON c.client_id = u.id 
                         WHERE c.lawyer_id = :lawyer_id 
                         ORDER BY c.created_at DESC");
        
        $this->db->bind(':lawyer_id', $lawyer_id);
        
        return $this->db->resultSet();
    }

    // Get consultation by ID
    public function getConsultationById($id) {
        $this->db->query("SELECT c.*, 
                         cl.name as client_name, cl.email as client_email,
                         l.name as lawyer_name, l.email as lawyer_email
                         FROM consultations c 
                         LEFT JOIN users cl ON c.client_id = cl.id 
                         LEFT JOIN users l ON c.lawyer_id = l.id 
                         WHERE c.id = :id");
        
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Get pending consultations count
    public function getPendingConsultationsCount($lawyer_id = null) {
        if ($lawyer_id) {
            $this->db->query("SELECT COUNT(*) as count FROM consultations WHERE status = 'pending' AND lawyer_id = :lawyer_id");
            $this->db->bind(':lawyer_id', $lawyer_id);
        } else {
            $this->db->query("SELECT COUNT(*) as count FROM consultations WHERE status = 'pending'");
        }
        
        $result = $this->db->single();
        return $result->count;
    }

    // Get all lawyers for consultation selection
    public function getAllLawyers() {
        $this->db->query("SELECT id, name, email FROM users WHERE role = 'lawyer' ORDER BY name ASC");
        
        return $this->db->resultSet();
    }
}
