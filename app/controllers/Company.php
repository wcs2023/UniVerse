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

        // Cache company profile essentials for header rendering
        $companyId = $_SESSION['user_id'];
        $profile = $this->companyProfileModel->getProfileByUserId($companyId);

        // Normalize/migrate legacy stored logo paths if needed
        if ($profile && !empty($profile['logo_url'])) {
            $projectRoot = dirname(__DIR__, 2);
            $raw = is_string($profile['logo_url']) ? $profile['logo_url'] : '';
            $raw = trim($raw);

            // Normalize to be relative to BASE_URL (BASE_URL already points to /public)
            $normalized = ltrim($raw, '/');
            if (strpos($normalized, 'public/') === 0) {
                $normalized = substr($normalized, 7);
            }

            // If it's a full URL, attempt to extract path under /public/
            if (preg_match('#^https?://#i', $normalized)) {
                $parsed = parse_url($normalized);
                $path = is_array($parsed) ? ($parsed['path'] ?? '') : '';
                $path = ltrim($path, '/');
                $publicPos = strpos($path, 'public/');
                if ($publicPos !== false) {
                    $normalized = substr($path, $publicPos + 7);
                } else {
                    $normalized = $path;
                }
            }

            // Only handle expected upload paths
            if (preg_match('#^uploads/company_profiles/[^/\\\\]+\.(jpg|jpeg|png|gif)$#i', $normalized)) {
                $targetAbs = $projectRoot . '/public/' . $normalized;
                $targetDir = dirname($targetAbs);

                // If target file missing, try to migrate from legacy location: DOCUMENT_ROOT/public
                if (!file_exists($targetAbs) && isset($_SERVER['DOCUMENT_ROOT'])) {
                    $legacyAbs = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/public/' . $normalized;
                    if (file_exists($legacyAbs)) {
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                        }

                        // Copy then attempt to delete legacy file
                        if (@copy($legacyAbs, $targetAbs)) {
                            @unlink($legacyAbs);
                        }
                    }
                }

                // If DB still contains legacy prefix, update it
                if ($raw !== $normalized) {
                    $this->companyProfileModel->updateLogoUrl($companyId, $normalized);
                    $profile['logo_url'] = $normalized;
                }
            }
        }
        if ($profile) {
            $_SESSION['company_logo_url'] = $profile['logo_url'] ?? null;
            $_SESSION['company_name'] = $profile['company_name'] ?? null;
        } else {
            $_SESSION['company_logo_url'] = null;
            $_SESSION['company_name'] = null;
        }
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
     * View an applicant profile (company-side, read-only)
     * Only allowed if the applicant applied to a job owned by this company.
     */
    public function applicantProfile($applicantUserId = null)
    {
        $companyId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($companyId);

        $applicantUserId = is_numeric($applicantUserId) ? (int)$applicantUserId : 0;
        if ($applicantUserId <= 0) {
            $_SESSION['error'] = 'Invalid applicant selected';
            header('Location: ' . BASE_URL . '/company/applications');
            exit;
        }

        // Access control
        if (!$this->applicationModel->companyHasApplicant($companyId, $applicantUserId)) {
            $_SESSION['error'] = 'Access denied';
            header('Location: ' . BASE_URL . '/company/applications');
            exit;
        }

        $applicant = $this->userModel->getUserById($applicantUserId);
        if (!$applicant) {
            $_SESSION['error'] = 'Applicant not found';
            header('Location: ' . BASE_URL . '/company/applications');
            exit;
        }

        $undergradProfileModel = $this->model('UndergraduateProfile');
        $achievementModel = $this->model('Achievement');
        $articleModel = $this->model('ArticleModel');

        $undergradProfile = $undergradProfileModel->getProfileByUserId($applicantUserId);
        $achievements = $achievementModel->getAchievementsByUserId($applicantUserId);

        // Only show published content
        $articles = $articleModel->getArticlesByStatus($applicantUserId, 'published');
        if (is_array($articles) && count($articles) > 20) {
            $articles = array_slice($articles, 0, 20);
        }

        $data = [
            'user' => $user,
            'applicant' => $applicant,
            'undergradProfile' => $undergradProfile,
            'achievements' => $achievements ?? [],
            'articles' => $articles ?? []
        ];

        $this->view('actors/company/applicant_profile', $data);
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
     * Get Application Details (AJAX)
     */
    public function getApplicationDetails($applicationId = null)
    {
        header('Content-Type: application/json');
        
        if (!$applicationId) {
            echo json_encode(['success' => false, 'message' => 'Application ID not provided']);
            exit;
        }
        
        $companyId = $_SESSION['user_id'];
        
        // Get application details
        $application = $this->applicationModel->getApplicationById($applicationId);
        
        if ($application && $application['company_id'] == $companyId) {
            echo json_encode([
                'success' => true,
                'application' => $application
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Application not found or access denied']);
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
            
            // Handle profile picture upload
            if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['size'] > 0) {
                $file = $_FILES['profilePicture'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 5 * 1024 * 1024; // 5MB
                
                if (!in_array($file['type'], $allowed_types)) {
                    $_SESSION['error'] = 'Only JPG, PNG, and GIF images are allowed';
                    $this->view('actors/company/profile', ['user' => $user, 'profile' => $profile]);
                    return;
                }
                
                if ($file['size'] > $max_size) {
                    $_SESSION['error'] = 'File size cannot exceed 5MB';
                    $this->view('actors/company/profile', ['user' => $user, 'profile' => $profile]);
                    return;
                }
                
                // Create upload directory if it doesn't exist
                $projectRoot = dirname(__DIR__, 2);
                $upload_dir = $projectRoot . '/public/uploads/company_profiles/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                // Generate unique filename
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'company_' . $companyId . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $filename;
                
                // Delete old profile picture if exists
                if ($profile && $profile['logo_url']) {
                    $oldRelative = ltrim($profile['logo_url'], '/');
                    if (strpos($oldRelative, 'public/') === 0) {
                        $oldRelative = substr($oldRelative, 7);
                    }

                    $old_file = $projectRoot . '/public/' . $oldRelative;
                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
                
                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    // Stored relative to BASE_URL (which already points to /public)
                    $data['logo_url'] = 'uploads/company_profiles/' . $filename;
                } else {
                    $_SESSION['error'] = 'Failed to upload image';
                    $this->view('actors/company/profile', ['user' => $user, 'profile' => $profile]);
                    return;
                }
            }
            
            // Validate phone number if provided (Sri Lankan format)
            if (!empty($data['contact_phone']) && !preg_match('/^\+94\d{9}$/', $data['contact_phone'])) {
                $_SESSION['error'] = 'Contact phone must be in format +94xxxxxxxxx (e.g., +94771234567)';
                $this->view('actors/company/profile', ['user' => $user, 'profile' => $profile]);
                return;
            }
            
            if ($profile) {
                if ($this->companyProfileModel->updateProfile($companyId, $data)) {
                    $_SESSION['success'] = 'Profile updated successfully!';
                    $profile = $this->companyProfileModel->getProfileByUserId($companyId);
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

            // Keep cached header logo in sync
            if ($profile) {
                $_SESSION['company_logo_url'] = $profile['logo_url'] ?? null;
                $_SESSION['company_name'] = $profile['company_name'] ?? null;
            }
        }
        
        $this->view('actors/company/profile', [
            'user' => $user,
            'profile' => $profile
        ]);
    }
}

