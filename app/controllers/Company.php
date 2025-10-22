<?php

class Company extends Controller
{
    private $jobModel;
    private $applicationModel;
    private $companyProfileModel;
    private $userModel;
    
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is a company
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'company') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        // Load models
        $this->jobModel = $this->model('Job');
        $this->applicationModel = $this->model('JobApplication');
        $this->companyProfileModel = $this->model('CompanyProfile');
        $this->userModel = $this->model('User');
    }
    
    /**
     * Company Dashboard / Landing Page
     */
    public function index()
    {
        $this->landing();
    }
    
    /**
     * Company Landing Page with Statistics
     */
    public function landing()
    {
        $companyId = $_SESSION['user_id'];
        
        // Get company profile
        $profile = $this->companyProfileModel->getProfileByUserId($companyId);
        
        // Get job statistics
        $stats = $this->jobModel->getCompanyJobStats($companyId);
        
        // Get recent jobs
        $recentJobs = $this->jobModel->getJobsByCompany($companyId);
        if ($recentJobs) {
            $recentJobs = array_slice($recentJobs, 0, 5); // Get only 5 recent jobs
        }
        
        // Get recent applications
        $recentApplications = $this->applicationModel->getApplicationsByCompany($companyId);
        if ($recentApplications) {
            $recentApplications = array_slice($recentApplications, 0, 5); // Get only 5 recent applications
        }
        
        // Get user data
        $user = $this->userModel->getUserById($companyId);
        
        $data = [
            'user' => $user,
            'profile' => $profile,
            'stats' => $stats,
            'recentJobs' => $recentJobs ?? [],
            'recentApplications' => $recentApplications ?? []
        ];
        
        $this->view('actors/company/landing', $data);
    }
    
    /**
     * Post New Job
     */
    public function postjobs()
    {
        $companyId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($companyId);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'company_id' => $companyId,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'requirements' => trim($_POST['requirements'] ?? ''),
                'responsibilities' => trim($_POST['responsibilities']),
                'location' => trim($_POST['location'] ?? ''),
                'job_type' => $_POST['job_type'] ?? 'full-time',
                'experience_level' => $_POST['experience_level'] ?? 'entry',
                'salary_min' => $_POST['salary_min'] ?? null,
                'salary_max' => $_POST['salary_max'] ?? null,
                'currency' => $_POST['currency'] ?? 'LKR',
                'application_deadline' => $_POST['application_deadline'] ?? null,
                'skills_required' => isset($_POST['skills']) ? json_encode($_POST['skills']) : null,
                'benefits' => trim($_POST['benefits'] ?? ''),
                'work_arrangement' => $_POST['work_arrangement'] ?? 'onsite',
                'contact_email' => trim($_POST['contact_email'] ?? ''),
                'contact_phone' => trim($_POST['contact_phone'] ?? ''),
                'application_url' => trim($_POST['application_url'] ?? ''),
                'status' => $_POST['status'] ?? 'active'
            ];
            
            // Validate required fields
            if (empty($data['title']) || empty($data['description']) || empty($data['responsibilities'])) {
                $_SESSION['error'] = 'Please fill in all required fields';
            } 
            // Validate phone number if provided (Sri Lankan format)
            elseif (!empty($data['contact_phone']) && !preg_match('/^\+94\d{9}$/', $data['contact_phone'])) {
                $_SESSION['error'] = 'Contact phone must be in format +94xxxxxxxxx (e.g., +94771234567)';
            } 
            else {
                // Create job
                $jobId = $this->jobModel->createJob($data);
                
                if ($jobId) {
                    $_SESSION['success'] = 'Job posted successfully!';
                    header('Location: ' . BASE_URL . '/company/managejobs');
                    exit;
                } else {
                    $_SESSION['error'] = 'Failed to create job posting';
                }
            }
        }
        
        $this->view('actors/company/postjobs', ['user' => $user]);
    }
    
    /**
     * Manage Jobs
     */
    public function managejobs()
    {
        $companyId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($companyId);
        
        // Get filter parameters
        $status = $_GET['status'] ?? null;
        
        // Get all jobs for this company
        $jobs = $this->jobModel->getJobsByCompany($companyId, $status);
        
        $data = [
            'user' => $user,
            'jobs' => $jobs ?? []
        ];
        
        $this->view('actors/company/managejobs', $data);
    }
    
    /**
     * View Job Details
     */
    public function jobdetails($jobId = null)
    {
        if (!$jobId) {
            header('Location: ' . BASE_URL . '/company/managejobs');
            exit;
        }
        
        $companyId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($companyId);
        
        // Get job details
        $job = $this->jobModel->getJobById($jobId);
        
        // Verify this job belongs to the company
        if (!$job || $job['company_id'] != $companyId) {
            $_SESSION['error'] = 'Job not found or access denied';
            header('Location: ' . BASE_URL . '/company/managejobs');
            exit;
        }
        
        // Get applications for this job
        $applications = $this->applicationModel->getApplicationsByJob($jobId);
        
        // Get application statistics
        $appStats = $this->applicationModel->getJobApplicationStats($jobId);
        
        $data = [
            'user' => $user,
            'job' => $job,
            'applications' => $applications ?? [],
            'appStats' => $appStats
        ];
        
        $this->view('actors/company/jobdetails', $data);
    }
    
    /**
     * View All Applications
     */
    public function applications()
    {
        $companyId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($companyId);
        
        // Get filter parameters
        $filters = [
            'status' => $_GET['status'] ?? null,
            'job_id' => $_GET['job_id'] ?? null
        ];
        
        // Get all applications for this company
        $applications = $this->applicationModel->getApplicationsByCompany($companyId, $filters);
        
        // Get all jobs for filter dropdown
        $jobs = $this->jobModel->getJobsByCompany($companyId);
        
        $data = [
            'user' => $user,
            'applications' => $applications ?? [],
            'jobs' => $jobs ?? []
        ];
        
        $this->view('actors/company/applications', $data);
    }
    
    /**
     * Update Application Status (AJAX)
     */
    public function updateApplicationStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $applicationId = $_POST['application_id'] ?? null;
            $status = $_POST['status'] ?? null;
            $notes = $_POST['notes'] ?? null;
            
            if ($applicationId && $status) {
                $companyId = $_SESSION['user_id'];
                
                // Verify this application belongs to a job of this company
                $application = $this->applicationModel->getApplicationById($applicationId);
                
                if ($application && $application['company_id'] == $companyId) {
                    $result = $this->applicationModel->updateApplicationStatus(
                        $applicationId, 
                        $status, 
                        $companyId, 
                        $notes
                    );
                    
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Application status updated']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Access denied']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            }
        }
        exit;
    }
    
    /**
     * Update Job Status (AJAX)
     */
    public function updatejobstatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jobId = $_POST['job_id'] ?? null;
            $status = $_POST['status'] ?? null;
            
            if ($jobId && $status) {
                $companyId = $_SESSION['user_id'];
                
                // Verify this job belongs to this company
                $job = $this->jobModel->getJobById($jobId);
                
                if ($job && $job['company_id'] == $companyId) {
                    $result = $this->jobModel->updateJob($jobId, ['status' => $status]);
                    
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Job status updated']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Access denied']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            }
        }
        exit;
    }
    
    /**
     * Edit Job
     */
    public function editjob($jobId = null)
    {
        if (!$jobId) {
            header('Location: ' . BASE_URL . '/company/managejobs');
            exit;
        }
        
        $companyId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($companyId);
        
        // Get job details
        $job = $this->jobModel->getJobById($jobId);
        
        // Debug: Log the job data
        error_log("Edit Job - Job ID: $jobId");
        error_log("Edit Job - Job data: " . print_r($job, true));
        
        // Verify this job belongs to the company
        if (!$job || $job['company_id'] != $companyId) {
            $_SESSION['error'] = 'Job not found or access denied';
            header('Location: ' . BASE_URL . '/company/managejobs');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Update job
            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'requirements' => trim($_POST['requirements'] ?? ''),
                'responsibilities' => trim($_POST['responsibilities']),
                'location' => trim($_POST['location'] ?? ''),
                'job_type' => $_POST['job_type'] ?? 'full-time',
                'experience_level' => $_POST['experience_level'] ?? 'entry',
                'salary_min' => $_POST['salary_min'] ?? null,
                'salary_max' => $_POST['salary_max'] ?? null,
                'application_deadline' => $_POST['application_deadline'] ?? null,
                'skills_required' => isset($_POST['skills']) ? json_encode($_POST['skills']) : null,
                'benefits' => trim($_POST['benefits'] ?? ''),
                'work_arrangement' => $_POST['work_arrangement'] ?? 'onsite',
                'contact_email' => trim($_POST['contact_email'] ?? ''),
                'contact_phone' => trim($_POST['contact_phone'] ?? ''),
                'status' => $_POST['status'] ?? 'active'
            ];
            
            // Validate phone number if provided (Sri Lankan format)
            if (!empty($data['contact_phone']) && !preg_match('/^\+94\d{9}$/', $data['contact_phone'])) {
                $_SESSION['error'] = 'Contact phone must be in format +94xxxxxxxxx (e.g., +94771234567)';
            }
            elseif ($this->jobModel->updateJob($jobId, $data)) {
                $_SESSION['success'] = 'Job updated successfully!';
                header('Location: ' . BASE_URL . '/company/managejobs');
                exit;
            } else {
                $_SESSION['error'] = 'Failed to update job';
            }
        }
        
        // Pass data correctly for the view
        $data = [
            'user' => $user,
            'job' => $job
        ];
        
        $this->view('actors/company/editjob', $data);
    }
    
    /**
     * Delete Job
     */
    public function deletejob($jobId = null)
    {
        if (!$jobId) {
            header('Location: ' . BASE_URL . '/company/managejobs');
            exit;
        }
        
        $companyId = $_SESSION['user_id'];
        
        // Get job to verify ownership
        $job = $this->jobModel->getJobById($jobId);
        
        if ($job && $job['company_id'] == $companyId) {
            if ($this->jobModel->deleteJob($jobId)) {
                $_SESSION['success'] = 'Job deleted successfully';
            } else {
                $_SESSION['error'] = 'Failed to delete job';
            }
        } else {
            $_SESSION['error'] = 'Job not found or access denied';
        }
        
        header('Location: ' . BASE_URL . '/company/managejobs');
        exit;
    }
    
    /**
     * Company Profile
     */
    public function profile()
    {
        $companyId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($companyId);
        $profile = $this->companyProfileModel->getProfileByUserId($companyId);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id' => $companyId,
                'company_name' => trim($_POST['company_name']),
                'company_size' => $_POST['company_size'] ?? null,
                'industry' => trim($_POST['industry'] ?? ''),
                'website' => trim($_POST['website'] ?? ''),
                'founded_year' => $_POST['founded_year'] ?? null,
                'company_description' => trim($_POST['company_description'] ?? ''),
                'contact_person' => trim($_POST['contact_person'] ?? ''),
                'contact_email' => trim($_POST['contact_email'] ?? ''),
                'contact_phone' => trim($_POST['contact_phone'] ?? '')
            ];
            
            // Validate phone number if provided (Sri Lankan format)
            if (!empty($data['contact_phone']) && !preg_match('/^\+94\d{9}$/', $data['contact_phone'])) {
                $_SESSION['error'] = 'Contact phone must be in format +94xxxxxxxxx (e.g., +94771234567)';
                $this->view('actors/company/profile', ['user' => $user, 'profile' => $profile]);
                return;
            }
            
            if ($profile) {
                if ($this->companyProfileModel->updateProfile($companyId, $data)) {
                    $_SESSION['success'] = 'Profile updated successfully!';
                } else {
                    $_SESSION['error'] = 'Failed to update profile';
                }
            } else {
                // Create new profile
                if ($this->companyProfileModel->createProfile($data)) {
                    $_SESSION['success'] = 'Profile created successfully!';
                    $profile = $this->companyProfileModel->getProfileByUserId($companyId);
                } else {
                    $_SESSION['error'] = 'Failed to create profile';
                }
            }
        }
        
        $this->view('actors/company/profile', [
            'user' => $user,
            'profile' => $profile
        ]);
    }
}

