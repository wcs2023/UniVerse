<?php

require_once '../app/PHPMailer/PHPMailer.php';
require_once '../app/PHPMailer/SMTP.php';
require_once '../app/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Reset extends Controller
{
    private $resetModel;

    public function __construct()
    {
        $this->resetModel = $this->model('ResetModel');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // VIEW 1: Email entry page
    public function index()
    {
        $this->view('auth/request_reset');
    }

    // ACTION: Generate OTP and send email
    public function generateOTP()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/reset');
            exit;
        }

        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('auth/request_reset', [
                'error' => 'Please enter a valid email address.'
            ]);
            return;
        }

        $otp = (string) random_int(100000, 999999);
        $otp_hash = hash("sha256", $otp);
        $expires_at = date("Y-m-d H:i:s", time() + 60 * 5);

        $data = [
            'email' => $email,
            'otp_hash' => $otp_hash,
            'expires_at' => $expires_at
        ];

        if ($this->resetModel->insertOTP($data)) {
            if ($this->sendOTPEmail($email, $otp)) {
                $_SESSION['reset_email'] = $email;
                $_SESSION['otp_sent'] = true;

                header('Location: ' . BASE_URL . '/reset/verify');
                exit;
            } else {
                $this->view('auth/request_reset', [
                    'error' => 'Failed to send email.'
                ]);
                return;
            }
        } else {
            $this->view('auth/request_reset', [
                'error' => 'Failed to generate OTP.'
            ]);
            return;
        }
    }

    // VIEW 2: OTP entry page
    public function verify()
    {
        if (!isset($_SESSION['otp_sent']) || !isset($_SESSION['reset_email'])) {
            header('Location: ' . BASE_URL . '/reset');
            exit;
        }

        $this->view('auth/verify_otp');
    }

    // ACTION: Verify OTP
    public function verifyOTP()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/reset/verify');
            exit;
        }

        $otp_entered = trim($_POST['otp'] ?? '');
        $email = $_SESSION['reset_email'] ?? '';

        if (empty($otp_entered) || empty($email)) {
            $this->view('auth/verify_otp', [
                'error' => 'Please enter the OTP.'
            ]);
            return;
        }

        $otp_entered_hash = hash("sha256", $otp_entered);
        $otp_row = $this->resetModel->getLatestValidOTP($email);

        if ($otp_row && hash_equals($otp_row['otp_hash'], $otp_entered_hash)) {
            unset($_SESSION['otp_sent']);
            $_SESSION['user_verified_to_reset'] = true;
            $this->resetModel->markOTPUsed($email);
            header('Location: ' . BASE_URL . '/reset/passwordchange');
            exit;
        } else {
            $this->view('auth/verify_otp', [
                'error' => 'Invalid or expired OTP.'
            ]);
            return;
        }
    }

    // VIEW 3: Password change page
    public function passwordchange()
    {
        if (!isset($_SESSION['user_verified_to_reset']) || !isset($_SESSION['reset_email'])) {
            header('Location: ' . BASE_URL . '/reset');
            exit;
        }

        $this->view('auth/passwordchange');
    }

    // ACTION: Update password
    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/reset');
            exit;
        }

        if (!isset($_SESSION['user_verified_to_reset']) || !isset($_SESSION['reset_email'])) {
            header('Location: ' . BASE_URL . '/reset');
            exit;
        }

        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');
        $email = $_SESSION['reset_email'];

        if (empty($password) || empty($confirm_password)) {
            $this->view('auth/passwordchange', [
                'error' => 'Please fill all fields.'
            ]);
            return;
        }

        if ($password !== $confirm_password) {
            $this->view('auth/passwordchange', [
                'error' => 'Passwords do not match.'
            ]);
            return;
        }

        if (strlen($password) < 8) {
            $this->view('auth/passwordchange', [
                'error' => 'Password must be at least 8 characters long.'
            ]);
            return;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Make sure your model has this method
        $updated = $this->resetModel->updateUserPassword($email, $hashed_password);

        if ($updated) {
            unset($_SESSION['user_verified_to_reset']);
            unset($_SESSION['reset_email']);
            unset($_SESSION['otp_sent']);

            $this->view('auth/login', [
                'success' => 'Password reset successful. Please log in.'
            ]);
        } else {
            $this->view('auth/passwordchange', [
                'error' => 'Failed to update password.'
            ]);
        }
    }

    private function sendOTPEmail($email, $otp)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $mail->Username = 'universeg027@gmail.com';
            $mail->Password = 'bycy jolk gfgf gfrm';

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('universeg027@gmail.com', 'UniVerse');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your Password Reset Code';
            $mail->Body = "
                <h2>Password Reset OTP</h2>
                <p>Your OTP is:</p>
                <h1 style='color:#483d8b;'>$otp</h1>
                <i>This code expires in 5 minutes.</i>
            ";
            $mail->AltBody = "Your OTP is: $otp. This code expires in 5 minutes.";

            return $mail->send();
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}