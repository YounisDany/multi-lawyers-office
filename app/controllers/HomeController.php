<?php

class HomeController extends Controller {
    public function __construct() {
        // Load models if needed
    }

    public function index() {
        $data = [
            'title' => 'منصة مكاتب المحاماة',
            'description' => 'منصة متكاملة تربط بين المحامين والعملاء لتقديم الخدمات القانونية بكفاءة وسهولة'
        ];
        $this->view('home', $data);
    }
}

