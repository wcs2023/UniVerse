<?php
require_once APPROOT . '/PHPMailer/PHPMailer.php';
require_once APPROOT . '/PHPMailer/SMTP.php';
require_once APPROOT . '/PHPMailer/Exception.php';

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

    // Step 1: show email form
    public function index()
    {
        $this->view('auth/request_reset');
    }

    // Step 2: generate OTP and send mail
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

        // check whether user exists before sending OTP
        if (!$this->resetModel->emailExists($email)) {
            $this->view('auth/request_reset', [
                'error' => 'No account found with that email address.'
            ]);
            return;
        }

        $otp = (string) random_int(100000, 999999);
        $otp_hash = hash('sha256', $otp);
        $expires_at = date('Y-m-d H:i:s', time() + 300); // 5 minutes

        $data = [
            'email' => $email,
            'otp_hash' => $otp_hash,
            'expires_at' => $expires_at
        ];

        // remove old unused OTPs first if needed
        // $this->resetModel->deleteOldOTPs($email);

        $inserted = $this->resetModel->insertOTP($data);

        if (!$inserted) {
            $this->view('auth/request_reset', [
                'error' => 'Failed to generate OTP.'
            ]);
            return;
        }

        $sent = $this->sendOTPEmail($email, $otp);

        if (!$sent) {
            $this->view('auth/request_reset', [
                'error' => 'Failed to send OTP email.'
            ]);
            return;
        }

        $_SESSION['reset_email'] = $email;
        $_SESSION['otp_sent'] = true;

        header('Location: ' . BASE_URL . '/reset/verify');
        exit;
    }

    // Step 3: show OTP form
    public function verify()
    {
        if (!isset($_SESSION['otp_sent']) || !isset($_SESSION['reset_email'])) {
            header('Location: ' . BASE_URL . '/reset');
            exit;
        }

        $this->view('auth/verify_otp');
    }

    // Step 4: verify OTP
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

        if (!preg_match('/^[0-9]{6}$/', $otp_entered)) {
            $this->view('auth/verify_otp', [
                'error' => 'OTP must be a 6-digit number.'
            ]);
            return;
        }

        $otp_hash = hash('sha256', $otp_entered);
        $otp_row = $this->resetModel->getLatestValidOTP($email);

        if (!$otp_row) {
            $this->view('auth/verify_otp', [
                'error' => 'Invalid or expired OTP.'
            ]);
            return;
        }

        if (!hash_equals($otp_row['otp_hash'], $otp_hash)) {
            $this->view('auth/verify_otp', [
                'error' => 'Invalid or expired OTP.'
            ]);
            return;
        }

        $this->resetModel->markOTPUsed($otp_row['id']);

        $_SESSION['user_verified_to_reset'] = true;
        unset($_SESSION['otp_sent']);

        header('Location: ' . BASE_URL . '/reset/passwordchange');
        exit;
    }

    // Step 5: show new password form
    public function passwordchange()
    {
        if (!isset($_SESSION['user_verified_to_reset']) || !isset($_SESSION['reset_email'])) {
            header('Location: ' . BASE_URL . '/reset');
            exit;
        }

        $this->view('auth/passwordchange');
    }

    // Step 6: update password
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
                'error' => 'Please fill in all fields.'
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

        $updated = $this->resetModel->updateUserPassword($email, $hashed_password);

        if (!$updated) {
            $this->view('auth/passwordchange', [
                'error' => 'Failed to update password.'
            ]);
            return;
        }

        // cleanup
        // $this->resetModel->deleteOldOTPs($email);

        unset($_SESSION['user_verified_to_reset']);
        unset($_SESSION['reset_email']);
        unset($_SESSION['otp_sent']);

        $this->view('auth/login', [
            'success' => 'Password reset successful. Please log in.'
        ]);
    }

    // Send OTP email
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
            $mail->Subject = 'Your Password Reset OTP';

            $mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 20px;'>
                    <h2 style='color: #6b46c1;'>Password Reset Request</h2>
                    <p>You requested to reset your password.</p>
                    <p>Use the OTP below to continue:</p>
                    <div style='font-size: 30px; font-weight: bold; letter-spacing: 4px; background: #f3f4f6; padding: 15px; text-align: center; border-radius: 8px;'>
                        {$otp}
                    </div>
                    <p style='margin-top: 20px;'>This OTP expires in <strong>5 minutes</strong>.</p>
                    <p>If you did not request this, please ignore this email.</p>
                </div>
            ";

            $mail->AltBody = "Your password reset OTP is: {$otp}. It expires in 5 minutes.";

            return $mail->send();
        } catch (Exception $e) {
            error_log('OTP Mail Error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}
