<?php

class StudentProfile extends Controller
{

    protected $userModel;
    protected $thread_model;
    protected $studentProfileModel;

    public function __construct()
    {
        $this->userModel = $this->model("User");
        $this->thread_model = $this->model("Forum_thread_model");
        $this->studentProfileModel = $this->model("Student_profile_model");
    }

    public function getCurrentUser()
    {
        return $_SESSION['USER'] ?? NULL;
    }

    public function getCurrentUserId()
    {
        $user = $this->getCurrentUser();
        return $user['user_id'] ?? null;
    }

    public function profile()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/Login/index');
            exit;
        }

        $user_id = $this->getCurrentUserId();


        $user = $this->userModel->getUserById($user_id);
        if (!$user) {
            session_destroy();
            header('Location: ' . BASE_URL . '/Login/index');
            exit;
        }
        $count = $this->thread_model->getThreadCountByUserId($user_id);

        // Simple profile data
        $data = [
            'count' => $count,
            'userData' => $user,
            'user_type' => $user['user_type']
        ];

        $this->view('actors/students/profile', $data);
    }

    // public function edit_profile()
    // {
    //     // 1) Must be logged in
    //     if (empty($_SESSION['user_id'])) {
    //         header('Location: ' . BASE_URL . '/Login/index');
    //         exit;
    //     }

    //     $user_id = (int) $_SESSION['user_id'];

    //     // 2) Load current user (for GET and for re-render after POST)
    //     $user = $this->userModel->getUserById($user_id);

    //     if (!$user) {
    //         session_destroy();
    //         header('Location: ' . BASE_URL . '/Login/index');
    //         exit;
    //     }

    //     // IMPORTANT: your view uses $data['user']['...']
    //     // So ensure $user is an array. If your model returns an object, convert it.
    //     if (is_object($user)) {
    //         $user = (array) $user;
    //     }

    //     $data = [
    //         'user' => $user,
    //         'error' => null,
    //         'success' => null
    //     ];

    //     // 3) If GET request -> just show the form
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         $this->view('actors/students/student_edit_profile', $data); // change path to your actual view file
    //         return;
    //     }

    //     // 4) Read & validate posted fields
    //     $first_name    = trim($_POST['first_name'] ?? '');
    //     $middle_name   = trim($_POST['middle_name'] ?? '');
    //     $last_name     = trim($_POST['last_name'] ?? '');
    //     $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    //     $gender        = trim($_POST['gender'] ?? '');
    //     $phone         = trim($_POST['phone'] ?? '');
    //     $address_line1 = trim($_POST['address_line1'] ?? '');

    //     $allowedGenders = ['male', 'female', 'other', ''];

    //     if ($first_name === '' || $last_name === '') {
    //         $data['error'] = 'First name and last name are required.';
    //         $data['user'] = array_merge($data['user'], [
    //             'first_name' => $first_name,
    //             'middle_name' => $middle_name,
    //             'last_name' => $last_name,
    //             'date_of_birth' => $date_of_birth,
    //             'gender' => $gender,
    //             'phone_number' => $phone,
    //             'address' => $address_line1,
    //         ]);
    //         $this->view('actors/students/student_edit_profile', $data);
    //         return;
    //     }

    //     if (!in_array($gender, $allowedGenders, true)) {
    //         $data['error'] = 'Invalid gender value.';
    //         $this->view('actors/students/student_edit_profile', $data);
    //         return;
    //     }

    //     // 5) Handle profile picture upload (optional)
    //     $newProfilePicturePath = null;

    //     if (!empty($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
    //         $file = $_FILES['profile_picture'];

    //         if ($file['error'] !== UPLOAD_ERR_OK) {
    //             $data['error'] = 'Image upload failed.';
    //             $this->view('actors/students/student_edit_profile', $data);
    //             return;
    //         }

    //         // 5MB limit (same as your JS)
    //         if ($file['size'] > 5 * 1024 * 1024) {
    //             $data['error'] = 'File size must be less than 5MB.';
    //             $this->view('actors/students/student_edit_profile', $data);
    //             return;
    //         }

    //         // Validate mime type
    //         $finfo = finfo_open(FILEINFO_MIME_TYPE);
    //         $mime  = finfo_file($finfo, $file['tmp_name']);
    //         finfo_close($finfo);

    //         $allowed = [
    //             'image/jpeg' => 'jpg',
    //             'image/png'  => 'png',
    //             'image/webp' => 'webp',
    //         ];

    //         if (!isset($allowed[$mime])) {
    //             $data['error'] = 'Please upload a valid image (JPG, PNG, WEBP).';
    //             $this->view('actors/students/student_edit_profile', $data);
    //             return;
    //         }

    //         $ext = $allowed[$mime];

    //         // Where to store (adjust to your project structure)
    //         $uploadDir = __DIR__ . '/../../public/uploads/profile_pictures';
    //         if (!is_dir($uploadDir)) {
    //             mkdir($uploadDir, 0755, true);
    //         }

    //         $filename = 'user_' . $user_id . '_' . time() . '.' . $ext;
    //         $fullPath = $uploadDir . '/' . $filename;

    //         if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
    //             $data['error'] = 'Could not save uploaded image.';
    //             $this->view('actors/students/student_edit_profile', $data);
    //             return;
    //         }

    //         // This is what you store in DB and later echo in the view like BASE_URL . $profilePicture
    //         $newProfilePicturePath = '/uploads/profile_pictures/' . $filename;
    //     }

    //     // 6) Update DB (you need to implement these in your model)
    //     $updateData = [
    //         'first_name'    => $first_name,
    //         'middle_name'   => $middle_name,
    //         'last_name'     => $last_name,
    //         'date_of_birth' => $date_of_birth,
    //         'gender'        => $gender,
    //         'phone_number'  => $phone,
    //         'address'       => $address_line1,
    //     ];

    //     if ($newProfilePicturePath !== null) {
    //         $updateData['profile_picture'] = $newProfilePicturePath;
    //     }

    //     $ok = $this->userModel->updateUser($user_id, $updateData);

    //     if (!$ok) {
    //         $data['error'] = 'Profile update failed. Please try again.';
    //         $this->view('actors/students/student_edit_profile', $data);
    //         return;
    //     }

    //     // 7) Reload user and show success
    //     $user = $this->userModel->getUserById($user_id);
    //     if (is_object($user)) {
    //         $user = (array) $user;
    //     }

    //     $data['user'] = $user;
    //     $data['success'] = 'Profile updated successfully.';
    //     $this->view('actors/students/student_edit_profile', $data);
    // }

    public function edit_Profile()
{
    if (empty($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/Login/index');
        exit;
    }

    $user_id = (int) $_SESSION['user_id'];

    $user = $this->userModel->getUserById($user_id);
    if (!$user) {
        session_destroy();
        header('Location: ' . BASE_URL . '/Login/index');
        exit;
    }

    $profilePic = !empty($user['profile_picture'])
        ? $user['profile_picture']
        : '/assets/images/default-avatar.png';

    // ===================== GET REQUEST =====================
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $data = [
            'user' => $user,
            'profilePic' => $profilePic,
            'error' => null,
            'success' => null,
        ];
        $this->view('actors/students/student_edit_profile', $data);
        return;
    }

    // ===================== POST DATA =====================
    $first_name    = trim($_POST['first_name'] ?? '');
    // $middle_name   = trim($_POST['middle_name'] ?? '');
    $last_name     = trim($_POST['last_name'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $gender        = trim($_POST['gender'] ?? '');
    $phone         = trim($_POST['phone'] ?? '');
    $address       = trim($_POST['address_line1'] ?? '');

    // Password fields
    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // ===================== FIX: IMAGE-ONLY DETECTION =====================
    $isImageOnly = isset($_FILES['profile_picture']) &&
        ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) &&
        empty($_POST['first_name']) &&
        empty($_POST['last_name']);

    // ===================== VALIDATION =====================
    if (!$isImageOnly) {
        if ($first_name === '' || $last_name === '') {
            $data = [
                'user' => $user,
                'profilePic' => $profilePic,
                'error' => 'First name and last name are required.',
                'success' => null
            ];
            $this->view('actors/students/student_edit_profile', $data);
            return;
        }
    }

    $allowedGenders = ['male', 'female', 'other', ''];
    if (!in_array($gender, $allowedGenders, true)) {
        $data = [
            'user' => $user,
            'profilePic' => $profilePic,
            'error' => 'Invalid gender value.',
            'success' => null
        ];
        $this->view('actors/students/student_edit_profile', $data);
        return;
    }

    // ===================== PASSWORD CHANGE =====================
    $wantsPasswordChange = ($current_password !== '' || $new_password !== '' || $confirm_password !== '');

    if ($wantsPasswordChange) {
        if ($current_password === '' || $new_password === '' || $confirm_password === '') {
            $data = ['user' => $user, 'profilePic' => $profilePic, 'error' => 'Fill all password fields.', 'success' => null];
            $this->view('actors/students/student_edit_profile', $data);
            return;
        }

        if ($new_password !== $confirm_password) {
            $data = ['user' => $user, 'profilePic' => $profilePic, 'error' => 'Passwords do not match.', 'success' => null];
            $this->view('actors/students/student_edit_profile', $data);
            return;
        }

        if (strlen($new_password) < 8) {
            $data = ['user' => $user, 'profilePic' => $profilePic, 'error' => 'Password must be at least 8 characters.', 'success' => null];
            $this->view('actors/students/student_edit_profile', $data);
            return;
        }

        $hash = $this->studentProfileModel->getPasswordHashByUserId($user_id);
        if (!$hash || !password_verify($current_password, $hash)) {
            $data = ['user' => $user, 'profilePic' => $profilePic, 'error' => 'Current password is incorrect.', 'success' => null];
            $this->view('actors/students/student_edit_profile', $data);
            return;
        }

        $newHash = password_hash($new_password, PASSWORD_DEFAULT);
        $okPwd = $this->studentProfileModel->updatePasswordHash($user_id, $newHash);

        if (!$okPwd) {
            $data = ['user' => $user, 'profilePic' => $profilePic, 'error' => 'Password update failed.', 'success' => null];
            $this->view('actors/students/student_edit_profile', $data);
            return;
        }
    }

    // ===================== IMAGE UPLOAD =====================
    $newProfilePicturePath = null;

    if (!empty($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {

        $file = $_FILES['profile_picture'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $data = ['user' => $user, 'profilePic' => $profilePic, 'error' => 'Image upload failed.', 'success' => null];
            $this->view('actors/students/student_edit_profile', $data);
            return;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            $data = ['user' => $user, 'profilePic' => $profilePic, 'error' => 'Max file size is 5MB.', 'success' => null];
            $this->view('actors/students/student_edit_profile', $data);
            return;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($allowed[$mime])) {
            $data = ['user' => $user, 'profilePic' => $profilePic, 'error' => 'Only JPG, PNG, WEBP allowed.', 'success' => null];
            $this->view('actors/students/student_edit_profile', $data);
            return;
        }

        $ext = $allowed[$mime];

        $uploadDir = __DIR__ . '/../../public/uploads/profile_pictures';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = 'user_' . $user_id . '_' . time() . '.' . $ext;
        $dest = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $data = ['user' => $user, 'profilePic' => $profilePic, 'error' => 'File save failed.', 'success' => null];
            $this->view('actors/students/student_edit_profile', $data);
            return;
        }

        // ✅ FIXED PATH
        $newProfilePicturePath = '/uploads/profile_pictures/' . $filename;
    }

    // ===================== UPDATE PROFILE =====================
    $updateData = [
        'first_name'    => $first_name ?: $user['first_name'],
        // 'middle_name'   => $middle_name,
        'last_name'     => $last_name ?: $user['last_name'],
        'date_of_birth' => $date_of_birth,
        'gender'        => $gender,
        'phone'         => $phone,
        'address_line1'       => $address,
    ];

    if ($newProfilePicturePath !== null) {
        $updateData['profile_picture'] = $newProfilePicturePath;
    }

    $okProfile = $this->studentProfileModel->updateUserProfile($user_id, $updateData);

    if (!$okProfile) {
        $data = ['user' => $user, 'profilePic' => $profilePic, 'error' => 'Profile update failed.', 'success' => null];
        $this->view('actors/students/student_edit_profile', $data);
        return;
    }

    // ===================== SUCCESS =====================
    $user = $this->userModel->getUserById($user_id);

    $profilePic = !empty($user['profile_picture'])
        ? $user['profile_picture']
        : '/assets/images/default-avatar.png';

    $successMsg = 'Profile updated successfully.';
    if ($wantsPasswordChange) $successMsg .= ' Password changed successfully.';

    $data = [
        'user' => $user,
        'profilePic' => $profilePic,
        'error' => null,
        'success' => $successMsg,
    ];

    $this->view('actors/students/student_edit_profile', $data);
}

    
}
