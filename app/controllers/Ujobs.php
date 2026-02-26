<?php

class Ujobs extends Controller
{
    private $jobModel;
    private $applicationModel;

    public function __construct()
    {
        $this->jobModel = $this->model('Job');
        $this->applicationModel = $this->model('JobApplication');
    }

    /**
     * Display all active job listings
     */
    public function index()
    {
        // Get filter parameters
        $filters = [
            'job_type' => $_GET['job_type'] ?? '',
            'experience_level' => $_GET['experience_level'] ?? '',
            'location' => $_GET['location'] ?? ''
        ];

        // Get all active jobs with filters
        $jobs = $this->jobModel->getAllActiveJobs($filters);

        $data = [
            'jobs' => $jobs,
            'filters' => $filters
        ];

        $this->view('actors/undergraduate/Ujobs', $data);
    }

    /**
     * View job details
     */
    public function viewDetails($jobId)
    {
        if (!$jobId) {
            header('Location: ' . BASE_URL . '/ujobs');
            exit;
        }

        // Get job details
        $job = $this->jobModel->getJobById($jobId);

        if (!$job) {
            header('Location: ' . BASE_URL . '/ujobs');
            exit;
        }

        // Increment views count
        $this->jobModel->incrementViews($jobId);

        // Check if user has already applied
        $hasApplied = false;
        if (isset($_SESSION['user_id'])) {
            $hasApplied = $this->applicationModel->hasUserApplied($jobId, $_SESSION['user_id']);
        }

        $data = [
            'job' => $job,
            'hasApplied' => $hasApplied
        ];

        $this->view('actors/undergraduate/UjobDetails', $data);
    }

    /**
     * Apply for a job - show application form
     */
    public function apply($jobId)
    {
        if (!$jobId) {
            header('Location: ' . BASE_URL . '/ujobs');
            exit;
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_url'] = BASE_URL . '/ujobs/apply/' . $jobId;
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $job = $this->jobModel->getJobById($jobId);

        if (!$job) {
            header('Location: ' . BASE_URL . '/ujobs');
            exit;
        }

        // Check if already applied
        if ($this->applicationModel->hasUserApplied($jobId, $_SESSION['user_id'])) {
            $_SESSION['error'] = 'You have already applied for this position.';
            header('Location: ' . BASE_URL . '/ujobs/viewDetails/' . $jobId);
            exit;
        }

        $data = [
            'job' => $job
        ];

        $this->view('actors/undergraduate/UapplyJob', $data);
    }

    /**
     * Submit job application
     */
    public function submitApplication($jobId)
    {
        if (!$jobId) {
            header('Location: ' . BASE_URL . '/ujobs');
            exit;
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Verify POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/ujobs/apply/' . $jobId);
            exit;
        }

        $job = $this->jobModel->getJobById($jobId);

        if (!$job) {
            header('Location: ' . BASE_URL . '/ujobs');
            exit;
        }

        // Check if already applied
        if ($this->applicationModel->hasUserApplied($jobId, $_SESSION['user_id'])) {
            $_SESSION['error'] = 'You have already applied for this position.';
            header('Location: ' . BASE_URL . '/ujobs/viewDetails/' . $jobId);
            exit;
        }

        // Validate cover letter
        $coverLetter = trim($_POST['cover_letter'] ?? '');
        if (empty($coverLetter)) {
            $_SESSION['error'] = 'Cover letter is required.';
            header('Location: ' . BASE_URL . '/ujobs/apply/' . $jobId);
            exit;
        }

        if (strlen($coverLetter) < 50) {
            $_SESSION['error'] = 'Cover letter must be at least 50 characters.';
            header('Location: ' . BASE_URL . '/ujobs/apply/' . $jobId);
            exit;
        }

        // Handle resume upload
        $resumeUrl = null;
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            $fileType = $_FILES['resume']['type'];
            $fileSize = $_FILES['resume']['size'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if (!in_array($fileType, $allowedTypes)) {
                $_SESSION['error'] = 'Invalid file type. Only PDF, DOC, and DOCX are allowed.';
                header('Location: ' . BASE_URL . '/ujobs/apply/' . $jobId);
                exit;
            }

            if ($fileSize > $maxSize) {
                $_SESSION['error'] = 'File size exceeds 5MB limit.';
                header('Location: ' . BASE_URL . '/ujobs/apply/' . $jobId);
                exit;
            }

            // Create upload directory if it doesn't exist
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/UniVerse/public/uploads/resumes/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $extension = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
            $fileName = 'resume_' . $_SESSION['user_id'] . '_' . $jobId . '_' . time() . '.' . $extension;
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['resume']['tmp_name'], $filePath)) {
                $resumeUrl = '/UniVerse/public/uploads/resumes/' . $fileName;
            } else {
                $_SESSION['error'] = 'Failed to upload resume. Please try again.';
                header('Location: ' . BASE_URL . '/ujobs/apply/' . $jobId);
                exit;
            }
        } else {
            $_SESSION['error'] = 'Resume is required.';
            header('Location: ' . BASE_URL . '/ujobs/apply/' . $jobId);
            exit;
        }

        // Prepare application data
        $applicationData = [
            'job_id' => $jobId,
            'user_id' => $_SESSION['user_id'],
            'cover_letter' => $coverLetter,
            'resume_url' => $resumeUrl,
            'portfolio_url' => !empty($_POST['portfolio_url']) ? trim($_POST['portfolio_url']) : null,
            'expected_salary' => !empty($_POST['expected_salary']) ? floatval($_POST['expected_salary']) : null,
            'availability_date' => !empty($_POST['availability_date']) ? $_POST['availability_date'] : null,
            'status' => 'pending'
        ];

        // Create application
        $result = $this->applicationModel->createApplication($applicationData);

        if ($result) {
            $_SESSION['success'] = 'Your application has been submitted successfully!';
            header('Location: ' . BASE_URL . '/ujobs/myApplications');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to submit application. Please try again.';
            header('Location: ' . BASE_URL . '/ujobs/apply/' . $jobId);
            exit;
        }
    }

    /**
     * View user's job applications
     */
    public function myApplications()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $applications = $this->applicationModel->getApplicationsByUser($_SESSION['user_id']);

        $data = [
            'applications' => $applications ?? []
        ];

        $this->view('actors/undergraduate/UmyApplications', $data);
    }

    /**
     * Withdraw job application
     */
    public function withdrawApplication($applicationId)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($applicationId) {
            $result = $this->applicationModel->withdrawApplication($applicationId, $_SESSION['user_id']);
            
            if ($result) {
                $_SESSION['success'] = 'Application withdrawn successfully.';
            } else {
                $_SESSION['error'] = 'Failed to withdraw application.';
            }
        }

        header('Location: ' . BASE_URL . '/ujobs/myApplications');
        exit;
    }
}
