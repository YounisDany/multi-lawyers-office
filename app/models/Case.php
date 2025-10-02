<?php

class CaseModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Create new case
    public function createCase($data) {
        $this->db->query("INSERT INTO cases (lawyer_id, client_id, title, details, status) VALUES (:lawyer_id, :client_id, :title, :details, :status)");
        
        $this->db->bind(':lawyer_id', $data['lawyer_id']);
        $this->db->bind(':client_id', $data['client_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':details', $data['details']);
        $this->db->bind(':status', $data['status']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get cases by client ID
    public function getCasesByClient($client_id) {
        $this->db->query("SELECT c.*, u.name as lawyer_name FROM cases c 
                         LEFT JOIN users u ON c.lawyer_id = u.id 
                         WHERE c.client_id = :client_id 
                         ORDER BY c.created_at DESC");
        
        $this->db->bind(':client_id', $client_id);
        
        return $this->db->resultSet();
    }

    // Get cases by lawyer ID
    public function getCasesByLawyer($lawyer_id) {
        $this->db->query("SELECT c.*, u.name as client_name FROM cases c 
                         LEFT JOIN users u ON c.client_id = u.id 
                         WHERE c.lawyer_id = :lawyer_id 
                         ORDER BY c.created_at DESC");
        
        $this->db->bind(':lawyer_id', $lawyer_id);
        
        return $this->db->resultSet();
    }

    // Get case by ID
    public function getCaseById($id) {
        $this->db->query("SELECT c.*, 
                         cl.name as client_name, cl.email as client_email,
                         l.name as lawyer_name, l.email as lawyer_email
                         FROM cases c 
                         LEFT JOIN users cl ON c.client_id = cl.id 
                         LEFT JOIN users l ON c.lawyer_id = l.id 
                         WHERE c.id = :id");
        
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Update case status
    public function updateCaseStatus($id, $status) {
        $this->db->query("UPDATE cases SET status = :status WHERE id = :id");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get all cases (for admin)
    public function getAllCases() {
        $this->db->query("SELECT c.*, 
                         cl.name as client_name,
                         l.name as lawyer_name
                         FROM cases c 
                         LEFT JOIN users cl ON c.client_id = cl.id 
                         LEFT JOIN users l ON c.lawyer_id = l.id 
                         ORDER BY c.created_at DESC");
        
        return $this->db->resultSet();
    }

    // Get cases count by status
    public function getCasesCountByStatus($status = null) {
        if ($status) {
            $this->db->query("SELECT COUNT(*) as count FROM cases WHERE status = :status");
            $this->db->bind(':status', $status);
        } else {
            $this->db->query("SELECT COUNT(*) as count FROM cases");
        }
        
        $result = $this->db->single();
        return $result->count;
    }
}
