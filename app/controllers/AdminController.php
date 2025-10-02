<?php

class AdminController extends Controller {
    public function __construct() {
        // Check if user is logged in and has admin role
        if (!Auth::isLoggedIn() || !Auth::hasRole('admin')) {
            redirect('login');
        }
        
        $this->caseModel = $this->model('CaseModel');
        $this->messageModel = $this->model('Message');
        $this->consultationModel = $this->model('Consultation');
        $this->userModel = $this->model('User');
    }

    public function dashboard() {
        // Get overall statistics
        $stats = [
            'total_users' => $this->getTotalUsers(),
            'total_lawyers' => $this->getTotalLawyers(),
            'total_clients' => $this->getTotalClients(),
            'total_cases' => $this->caseModel->getCasesCountByStatus(),
            'new_cases' => $this->caseModel->getCasesCountByStatus('new'),
            'in_progress_cases' => $this->caseModel->getCasesCountByStatus('in_progress'),
            'closed_cases' => $this->caseModel->getCasesCountByStatus('closed'),
            'pending_consultations' => $this->consultationModel->getPendingConsultationsCount()
        ];
        
        // Get recent cases
        $recentCases = $this->caseModel->getAllCases();
        $recentCases = array_slice($recentCases, 0, 10); // Get latest 10 cases
        
        $data = [
            'title' => 'لوحة تحكم المدير',
            'stats' => $stats,
            'recent_cases' => $recentCases,
            'user_name' => $_SESSION['user_name']
        ];
        
        $this->view('admin/dashboard', $data);
    }

    public function lawyers() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];
            $user_id = $_POST['user_id'];
            
            if ($action == 'delete') {
                // Delete lawyer (you might want to add more validation)
                $this->deleteUser($user_id);
                redirect('admin/lawyers');
            }
        }
        
        // Get all lawyers
        $lawyers = $this->getLawyers();
        
        $data = [
            'lawyers' => $lawyers
        ];
        
        $this->view('admin/lawyers', $data);
    }

    public function cases() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
            $case_id = $_POST['case_id'];
            $new_status = $_POST['status'];
            
            if ($this->caseModel->updateCaseStatus($case_id, $new_status)) {
                redirect('admin/cases');
            } else {
                die('حدث خطأ ما');
            }
        }
        
        $cases = $this->caseModel->getAllCases();
        
        $data = [
            'cases' => $cases
        ];
        
        $this->view('admin/cases', $data);
    }

    public function reports() {
        // Get comprehensive statistics
        $stats = [
            'total_users' => $this->getTotalUsers(),
            'total_lawyers' => $this->getTotalLawyers(),
            'total_clients' => $this->getTotalClients(),
            'total_cases' => $this->caseModel->getCasesCountByStatus(),
            'new_cases' => $this->caseModel->getCasesCountByStatus('new'),
            'in_progress_cases' => $this->caseModel->getCasesCountByStatus('in_progress'),
            'closed_cases' => $this->caseModel->getCasesCountByStatus('closed'),
            'archived_cases' => $this->caseModel->getCasesCountByStatus('archived'),
            'total_consultations' => $this->getTotalConsultations(),
            'pending_consultations' => $this->consultationModel->getPendingConsultationsCount(),
            'answered_consultations' => $this->getAnsweredConsultations()
        ];
        
        // Get monthly statistics (simplified)
        $monthlyStats = $this->getMonthlyStats();
        
        $data = [
            'stats' => $stats,
            'monthly_stats' => $monthlyStats
        ];
        
        $this->view('admin/reports', $data);
    }

    // Helper methods
    private function getTotalUsers() {
        $this->userModel->db->query("SELECT COUNT(*) as count FROM users");
        $result = $this->userModel->db->single();
        return $result->count;
    }

    private function getTotalLawyers() {
        $this->userModel->db->query("SELECT COUNT(*) as count FROM users WHERE role = 'lawyer'");
        $result = $this->userModel->db->single();
        return $result->count;
    }

    private function getTotalClients() {
        $this->userModel->db->query("SELECT COUNT(*) as count FROM users WHERE role = 'client'");
        $result = $this->userModel->db->single();
        return $result->count;
    }

    private function getLawyers() {
        $this->userModel->db->query("SELECT * FROM users WHERE role = 'lawyer' ORDER BY created_at DESC");
        return $this->userModel->db->resultSet();
    }

    private function deleteUser($user_id) {
        $this->userModel->db->query("DELETE FROM users WHERE id = :id");
        $this->userModel->db->bind(':id', $user_id);
        return $this->userModel->db->execute();
    }

    private function getTotalConsultations() {
        $this->consultationModel->db->query("SELECT COUNT(*) as count FROM consultations");
        $result = $this->consultationModel->db->single();
        return $result->count;
    }

    private function getAnsweredConsultations() {
        $this->consultationModel->db->query("SELECT COUNT(*) as count FROM consultations WHERE status = 'answered'");
        $result = $this->consultationModel->db->single();
        return $result->count;
    }

    private function getMonthlyStats() {
        // Simplified monthly stats - you can expand this
        $this->caseModel->db->query("SELECT 
            MONTH(created_at) as month, 
            COUNT(*) as cases_count 
            FROM cases 
            WHERE YEAR(created_at) = YEAR(CURDATE()) 
            GROUP BY MONTH(created_at) 
            ORDER BY month");
        
        return $this->caseModel->db->resultSet();
    }
}
