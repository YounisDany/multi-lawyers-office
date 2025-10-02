<?php

class LawyerController extends Controller {
    public function __construct() {
        // Check if user is logged in and has lawyer role
        if (!Auth::isLoggedIn() || !Auth::hasRole('lawyer')) {
            redirect('login');
        }
        
        $this->caseModel = $this->model('CaseModel');
        $this->messageModel = $this->model('Message');
        $this->consultationModel = $this->model('Consultation');
        $this->userModel = $this->model('User');
    }

    public function dashboard() {
        $lawyer_id = Auth::userId();
        
        // Get lawyer's cases
        $cases = $this->caseModel->getCasesByLawyer($lawyer_id);
        
        // Get recent messages
        $messages = $this->messageModel->getLatestMessages($lawyer_id, 5);
        
        // Get pending consultations
        $consultations = $this->consultationModel->getConsultationsByLawyer($lawyer_id);
        $pendingCount = $this->consultationModel->getPendingConsultationsCount($lawyer_id);
        
        // Get statistics
        $stats = [
            'total_cases' => count($cases),
            'new_cases' => count(array_filter($cases, function($case) { return $case->status == 'new'; })),
            'in_progress_cases' => count(array_filter($cases, function($case) { return $case->status == 'in_progress'; })),
            'pending_consultations' => $pendingCount
        ];
        
        $data = [
            'title' => 'لوحة تحكم المحامي',
            'cases' => $cases,
            'messages' => $messages,
            'consultations' => $consultations,
            'stats' => $stats,
            'user_name' => $_SESSION['user_name']
        ];
        
        $this->view('lawyer/dashboard', $data);
    }

    public function cases() {
        $lawyer_id = Auth::userId();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
            // Handle case status update
            $case_id = $_POST['case_id'];
            $new_status = $_POST['status'];
            
            if ($this->caseModel->updateCaseStatus($case_id, $new_status)) {
                redirect('lawyer/cases');
            } else {
                die('حدث خطأ ما');
            }
        }
        
        $cases = $this->caseModel->getCasesByLawyer($lawyer_id);
        
        $data = [
            'cases' => $cases
        ];
        
        $this->view('lawyer/cases', $data);
    }

    public function consultations() {
        $lawyer_id = Auth::userId();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answer_consultation'])) {
            // Handle consultation answer
            $consultation_id = $_POST['consultation_id'];
            $answer = trim($_POST['answer']);
            
            if ($this->consultationModel->answerConsultation($consultation_id, $answer)) {
                redirect('lawyer/consultations');
            } else {
                die('حدث خطأ ما');
            }
        }
        
        $consultations = $this->consultationModel->getConsultationsByLawyer($lawyer_id);
        
        $data = [
            'consultations' => $consultations
        ];
        
        $this->view('lawyer/consultations', $data);
    }

    public function reports() {
        $lawyer_id = Auth::userId();
        
        // Get cases statistics
        $cases = $this->caseModel->getCasesByLawyer($lawyer_id);
        $consultations = $this->consultationModel->getConsultationsByLawyer($lawyer_id);
        
        $stats = [
            'total_cases' => count($cases),
            'new_cases' => count(array_filter($cases, function($case) { return $case->status == 'new'; })),
            'in_progress_cases' => count(array_filter($cases, function($case) { return $case->status == 'in_progress'; })),
            'closed_cases' => count(array_filter($cases, function($case) { return $case->status == 'closed'; })),
            'total_consultations' => count($consultations),
            'answered_consultations' => count(array_filter($consultations, function($consultation) { return $consultation->status == 'answered'; })),
            'pending_consultations' => count(array_filter($consultations, function($consultation) { return $consultation->status == 'pending'; }))
        ];
        
        $data = [
            'stats' => $stats,
            'cases' => $cases,
            'consultations' => $consultations
        ];
        
        $this->view('lawyer/reports', $data);
    }

    public function archive() {
        $lawyer_id = Auth::userId();
        
        // Get archived cases
        $this->caseModel->db->query("SELECT c.*, u.name as client_name FROM cases c 
                                   LEFT JOIN users u ON c.client_id = u.id 
                                   WHERE c.lawyer_id = :lawyer_id AND c.status = 'archived'
                                   ORDER BY c.created_at DESC");
        
        $this->caseModel->db->bind(':lawyer_id', $lawyer_id);
        $archivedCases = $this->caseModel->db->resultSet();
        
        $data = [
            'archived_cases' => $archivedCases
        ];
        
        $this->view('lawyer/archive', $data);
    }
}
