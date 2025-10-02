<?php

class ClientController extends Controller {
    public function __construct() {
        // Check if user is logged in and has client role
        if (!Auth::isLoggedIn() || !Auth::hasRole('client')) {
            redirect('login');
        }
        
        $this->caseModel = $this->model('CaseModel');
        $this->messageModel = $this->model('Message');
        $this->consultationModel = $this->model('Consultation');
        $this->userModel = $this->model('User');
    }

    public function dashboard() {
        $client_id = Auth::userId();
        
        // Get client's cases
        $cases = $this->caseModel->getCasesByClient($client_id);
        
        // Get recent messages
        $messages = $this->messageModel->getLatestMessages($client_id, 5);
        
        // Get consultations
        $consultations = $this->consultationModel->getConsultationsByClient($client_id);
        
        $data = [
            'title' => 'لوحة تحكم العميل',
            'cases' => $cases,
            'messages' => $messages,
            'consultations' => $consultations,
            'user_name' => $_SESSION['user_name']
        ];
        
        $this->view('client/dashboard', $data);
    }

    public function newCase() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'lawyer_id' => trim($_POST['lawyer_id']),
                'title' => trim($_POST['title']),
                'details' => trim($_POST['details']),
                'title_err' => '',
                'details_err' => '',
                'lawyer_err' => ''
            ];

            // Validate data
            if (empty($data['title'])) {
                $data['title_err'] = 'الرجاء إدخال عنوان القضية';
            }

            if (empty($data['details'])) {
                $data['details_err'] = 'الرجاء إدخال تفاصيل القضية';
            }

            if (empty($data['lawyer_id'])) {
                $data['lawyer_err'] = 'الرجاء اختيار محامي';
            }

            // Make sure errors are empty
            if (empty($data['title_err']) && empty($data['details_err']) && empty($data['lawyer_err'])) {
                $caseData = [
                    'lawyer_id' => $data['lawyer_id'],
                    'client_id' => Auth::userId(),
                    'title' => $data['title'],
                    'details' => $data['details'],
                    'status' => 'new'
                ];

                if ($this->caseModel->createCase($caseData)) {
                    redirect('client/dashboard');
                } else {
                    die('حدث خطأ ما');
                }
            } else {
                // Get lawyers for the form
                $data['lawyers'] = $this->consultationModel->getAllLawyers();
                $this->view('client/new_case', $data);
            }
        } else {
            // Get lawyers for the form
            $data = [
                'title' => '',
                'details' => '',
                'lawyer_id' => '',
                'title_err' => '',
                'details_err' => '',
                'lawyer_err' => '',
                'lawyers' => $this->consultationModel->getAllLawyers()
            ];

            $this->view('client/new_case', $data);
        }
    }

    public function caseDetails() {
        $case_id = $_GET['id'] ?? 0;
        
        $case = $this->caseModel->getCaseById($case_id);
        
        if (!$case || $case->client_id != Auth::userId()) {
            redirect('client/dashboard');
        }
        
        // Get messages for this case
        $messages = $this->messageModel->getMessagesByCase($case_id);
        
        $data = [
            'case' => $case,
            'messages' => $messages
        ];
        
        $this->view('client/case_details', $data);
    }

    public function consultations() {
        $client_id = Auth::userId();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle new consultation
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $consultationData = [
                'lawyer_id' => trim($_POST['lawyer_id']),
                'client_id' => $client_id,
                'question' => trim($_POST['question'])
            ];

            if ($this->consultationModel->createConsultation($consultationData)) {
                redirect('client/consultations');
            } else {
                die('حدث خطأ ما');
            }
        }
        
        $consultations = $this->consultationModel->getConsultationsByClient($client_id);
        $lawyers = $this->consultationModel->getAllLawyers();
        
        $data = [
            'consultations' => $consultations,
            'lawyers' => $lawyers
        ];
        
        $this->view('client/consultations', $data);
    }

    public function messages() {
        $case_id = $_GET['case_id'] ?? 0;
        
        $case = $this->caseModel->getCaseById($case_id);
        
        if (!$case || $case->client_id != Auth::userId()) {
            redirect('client/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle new message
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $messageData = [
                'case_id' => $case_id,
                'sender_id' => Auth::userId(),
                'message' => trim($_POST['message'])
            ];

            if ($this->messageModel->sendMessage($messageData)) {
                redirect('client/messages?case_id=' . $case_id);
            } else {
                die('حدث خطأ ما');
            }
        }
        
        $messages = $this->messageModel->getMessagesByCase($case_id);
        
        $data = [
            'case' => $case,
            'messages' => $messages
        ];
        
        $this->view('client/messages', $data);
    }
}
