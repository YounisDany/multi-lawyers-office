<?php

class AuthController extends Controller {
    public function __construct() {
        $this->userModel = $this->model("User");
    }

    public function register() {
        // Check for POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                "name" => trim($_POST["name"]),
                "email" => trim($_POST["email"]),
                "password" => trim($_POST["password"]),
                "confirm_password" => trim($_POST["confirm_password"]),
                "role" => trim($_POST["role"]),
                "name_err" => "",
                "email_err" => "",
                "password_err" => "",
                "confirm_password_err" => ""
            ];

            // Validate Email
            if (empty($data["email"])) {
                $data["email_err"] = "الرجاء إدخال البريد الإلكتروني";
            } else {
                // Check email
                if ($this->userModel->findUserByEmail($data["email"])) {
                    $data["email_err"] = "البريد الإلكتروني مسجل بالفعل";
                }
            }

            // Validate Name
            if (empty($data["name"])) {
                $data["name_err"] = "الرجاء إدخال الاسم";
            }

            // Validate Password
            if (empty($data["password"])) {
                $data["password_err"] = "الرجاء إدخال كلمة المرور";
            } elseif (strlen($data["password"]) < 6) {
                $data["password_err"] = "يجب أن تكون كلمة المرور 6 أحرف على الأقل";
            }

            // Validate Confirm Password
            if (empty($data["confirm_password"])) {
                $data["confirm_password_err"] = "الرجاء تأكيد كلمة المرور";
            } else {
                if ($data["password"] != $data["confirm_password"]) {
                    $data["confirm_password_err"] = "كلمات المرور غير متطابقة";
                }
            }

            // Make sure errors are empty
            if (empty($data["email_err"]) && empty($data["name_err"]) && empty($data["password_err"]) && empty($data["confirm_password_err"])) {
                // Hashed Password
                $data["password"] = hashPassword($data["password"]);

                // Register User
                if ($this->userModel->register($data)) {
                    redirect("login");
                } else {
                    die("حدث خطأ ما");
                }
            } else {
                // Load view with errors
                $this->view("auth/register", $data);
            }
        } else {
            // Init data
            $data = [
                "name" => "",
                "email" => "",
                "password" => "",
                "confirm_password" => "",
                "role" => "client",
                "name_err" => "",
                "email_err" => "",
                "password_err" => "",
                "confirm_password_err" => ""
            ];

            // Load view
            $this->view("auth/register", $data);
        }
    }

    public function login() {
        // Check for POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                "email" => trim($_POST["email"]),
                "password" => trim($_POST["password"]),
                "email_err" => "",
                "password_err" => ""
            ];

            // Validate Email
            if (empty($data["email"])) {
                $data["email_err"] = "الرجاء إدخال البريد الإلكتروني";
            }

            // Validate Password
            if (empty($data["password"])) {
                $data["password_err"] = "الرجاء إدخال كلمة المرور";
            }

            // Check for user/email
            if ($this->userModel->findUserByEmail($data["email"])) {
                // User found
            } else {
                // User not found
                $data["email_err"] = "المستخدم غير موجود";
            }

            // Make sure errors are empty
            if (empty($data["email_err"]) && empty($data["password_err"])) {
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data["email"], $data["password"]);

                if ($loggedInUser) {
                    // Create Session
                    Auth::login($loggedInUser);
                    if (Auth::hasRole("admin")) {
                        redirect("admin/dashboard");
                    } elseif (Auth::hasRole("lawyer")) {
                        redirect("lawyer/dashboard");
                    } else {
                        redirect("client/dashboard");
                    }
                } else {
                    $data["password_err"] = "كلمة المرور غير صحيحة";
                    $this->view("auth/login", $data);
                }
            } else {
                // Load view with errors
                $this->view("auth/login", $data);
            }
        } else {
            // Init data
            $data = [
                "email" => "",
                "password" => "",
                "email_err" => "",
                "password_err" => ""
            ];

            // Load view
            $this->view("auth/login", $data);
        }
    }

    public function logout() {
        Auth::logout();
        redirect("login");
    }
}

