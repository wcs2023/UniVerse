<?php 

class Contact extends Controller
{
    private $contactModel;

    public function __construct()
    {
        $this->contactModel = $this->model('ContactMessage');
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleContactForm();
        } else {
            $this->view('/layout/contact', []);
        }
    }

    public function contactmessages()
    {
        $contactModel = $this->model('ContactMessage');

        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'status' => $_GET['status'] ?? ''
        ];

        $messages = $contactModel->getAllMessages($filters);

        $data = [
            'messages' => $messages ?? []
        ];

        $this->view('actors/admin/contactmessages', $data);
    }

    public function viewcontactmessage($id = null)
    {
        if (!$id) {
            header('Location: ' . BASE_URL . '/contact/contactmessages');
            exit;
        }

        $contactModel = $this->model('ContactMessage');
        $message = $contactModel->getMessageById($id);

        if (!$message) {
            $_SESSION['error'] = 'Message not found';
            header('Location: ' . BASE_URL . '/contact/contactmessages');
            exit;
        }

        $data = [
            'message' => $message
        ];

        $this->view('actors/admin/viewcontactmessage', $data);
    }

    private function handleContactForm()
    {
        $data = [
            'user_id' => $_SESSION['user_id'] ?? null,
            'name' => htmlspecialchars(trim($_POST['name'] ?? '')),
            'email' => filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL),
            'subject' => htmlspecialchars(trim($_POST['subject'] ?? '')),
            'message' => htmlspecialchars(trim($_POST['message'] ?? ''))
        ];

        if (empty($data['name']) || empty($data['email']) || empty($data['subject']) || empty($data['message'])) {
            $_SESSION['error'] = 'All fields are required.';
            $this->view('/layout/contact', $data);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Please provide a valid email address.';
            $this->view('/layout/contact', $data);
            return;
        }

        if ($this->contactModel->createMessage($data)) {
            $_SESSION['success'] = 'Thank you for contacting us! We will get back to you within 24-48 hours.';
            header('Location: ' . BASE_URL . '/contact');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to submit your message. Please try again.';
            $this->view('/layout/contact', $data);
        }
    }
}