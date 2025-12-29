<?php 

class Contact extends Controller{

    public function index(){
        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleContactForm();
        } else {
            $this->view('/layout/contact');
        }
    }

    private function handleContactForm() {
        // Sanitize and validate form data
        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
        $message = htmlspecialchars(trim($_POST['message'] ?? ''));

        // Validate required fields
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $_SESSION['error'] = 'All fields are required.';
            $this->view('/layout/contact', [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message
            ]);
            return;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Please provide a valid email address.';
            $this->view('/layout/contact', [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message
            ]);
            return;
        }

        // Here you would typically:
        // 1. Save to database
        // 2. Send email notification
        // 3. Queue for admin review
        
        // For now, we'll just log it and show success message
        error_log("Contact Form Submission - Name: $name, Email: $email, Subject: $subject");

        // Set success message
        $_SESSION['success'] = 'Thank you for contacting us! We will get back to you within 24-48 hours.';
        
        // Redirect to prevent form resubmission
        header('Location: ' . BASE_URL . '/contact');
        exit;
    }
}
