<?php

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Register user
    public function register($data) {
        $this->db->query("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        // Bind values
        $this->db->bind(":name", $data["name"]);
        $this->db->bind(":email", $data["email"]);
        $this->db->bind(":password", $data["password"]);
        $this->db->bind(":role", $data["role"]);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login user
    public function login($email, $password) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(":email", $email);

        $row = $this->db->single();

        if ($row) {
            $hashed_password = $row->password;
            if (verifyPassword($password, $hashed_password)) {
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(":email", $email);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Get user by ID
    public function getUserById($id) {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(":id", $id);

        $row = $this->db->single();

        return $row;
    }
}

