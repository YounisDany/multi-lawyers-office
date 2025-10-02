<?php

class Message {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Send message
    public function sendMessage($data) {
        $this->db->query("INSERT INTO messages (case_id, sender_id, message, attachment) VALUES (:case_id, :sender_id, :message, :attachment)");
        
        $this->db->bind(':case_id', $data['case_id']);
        $this->db->bind(':sender_id', $data['sender_id']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':attachment', $data['attachment'] ?? null);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get messages by case ID
    public function getMessagesByCase($case_id) {
        $this->db->query("SELECT m.*, u.name as sender_name, u.role as sender_role 
                         FROM messages m 
                         LEFT JOIN users u ON m.sender_id = u.id 
                         WHERE m.case_id = :case_id 
                         ORDER BY m.created_at ASC");
        
        $this->db->bind(':case_id', $case_id);
        
        return $this->db->resultSet();
    }

    // Get latest messages for a user
    public function getLatestMessages($user_id, $limit = 10) {
        $this->db->query("SELECT DISTINCT m.case_id, m.message, m.created_at, c.title as case_title,
                         u.name as sender_name
                         FROM messages m 
                         LEFT JOIN cases c ON m.case_id = c.id
                         LEFT JOIN users u ON m.sender_id = u.id
                         WHERE (c.client_id = :user_id OR c.lawyer_id = :user_id)
                         AND m.sender_id != :user_id
                         ORDER BY m.created_at DESC 
                         LIMIT :limit");
        
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    // Mark messages as read (future feature)
    public function markAsRead($case_id, $user_id) {
        // This would require adding a 'read_by' table or field
        // For now, we'll just return true
        return true;
    }
}
