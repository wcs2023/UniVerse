<?php

class Company extends Controller{
    
    private $jobModel;
    
    public function __construct() {
        $this->jobModel = $this->loadModel('Job');
    }
    
    public function index(){
        $companyId = $_SESSION['user_id'] ?? 1; // TODO: replace with real session user id for company
        
        // Ensure model is loaded
        if (!$this->jobModel) {
            $this->jobModel = $this->loadModel('Job');
        }

        // Active job postings count
        $activeCount = $this->jobModel->count("company_id = {$companyId} AND status = 'active'");

        // Recent activity: last 5 jobs created/updated for this company
        $recent = $this->jobModel->query(
            "SELECT id, job_title, status, created_at, updated_at
             FROM jobs
             WHERE company_id = :cid
             ORDER BY GREATEST(created_at, updated_at) DESC
             LIMIT 5",
            ['cid' => $companyId]
        )->fetchAll();

        $data = [
            'activeCount' => $activeCount,
            'recent' => $recent
        ];

        $this->view('actors/company/landing', $data);
    }
    
    public function postjobs(){
        // Check if we're editing an existing job
        if (isset($_GET['id'])) {
            $jobId = $_GET['id'];
            $job = $this->jobModel->findById($jobId);
            
            if ($job) {
                $data['job'] = $job;
                $data['mode'] = 'edit';
            } else {
                // Job not found, redirect to managejobs
                header('Location: ' . BASE_URL . '/company/managejobs');
                exit;
            }
        } else {
            $data['mode'] = 'create';
        }
        
        $this->view('actors/company/postjobs', $data);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize input
            $data = [
                'company_id' => $_SESSION['user_id'] ?? 1, // Replace with actual session user ID
                'job_title' => trim($_POST['jobTitle']),
                'job_type' => $_POST['jobType'],
                'location' => trim($_POST['location']),
                'salary' => trim($_POST['salary']) ?: null,
                'deadline' => $_POST['deadline'],
                'description' => trim($_POST['description']),
                'requirements' => trim($_POST['requirements']),
                'responsibilities' => trim($_POST['responsibilities']) ?: null,
                'status' => $_POST['status'] ?? 'active'
            ];
            
            // Basic validation
            if (empty($data['job_title']) || empty($data['job_type']) || empty($data['location']) || 
                empty($data['deadline']) || empty($data['description']) || empty($data['requirements'])) {
                $_SESSION['error'] = 'Please fill in all required fields.';
                header('Location: ' . BASE_URL . '/company/postjobs');
                exit;
            }
            
            // Insert job into database
            $jobId = $this->jobModel->insert($data);
            
            if ($jobId) {
                $_SESSION['success'] = 'Job posted successfully!';
                header('Location: ' . BASE_URL . '/company/managejobs');
            } else {
                $_SESSION['error'] = 'Failed to post job. Please try again.';
                header('Location: ' . BASE_URL . '/company/postjobs');
            }
            exit;
        }
        
        // If not POST request, redirect to postjobs page
        header('Location: ' . BASE_URL . '/company/postjobs');
        exit;
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jobId = $_POST['job_id'];
            
            // Validate and sanitize input
            $data = [
                'job_title' => trim($_POST['jobTitle']),
                'job_type' => $_POST['jobType'],
                'location' => trim($_POST['location']),
                'salary' => trim($_POST['salary']) ?: null,
                'deadline' => $_POST['deadline'],
                'description' => trim($_POST['description']),
                'requirements' => trim($_POST['requirements']),
                'responsibilities' => trim($_POST['responsibilities']) ?: null,
                'status' => $_POST['status'] ?? 'active'
            ];
            
            // Basic validation
            if (empty($data['job_title']) || empty($data['job_type']) || empty($data['location']) || 
                empty($data['deadline']) || empty($data['description']) || empty($data['requirements'])) {
                $_SESSION['error'] = 'Please fill in all required fields.';
                header('Location: ' . BASE_URL . '/company/postjobs?id=' . $jobId);
                exit;
            }
            
            // Update job in database
            $updated = $this->jobModel->update($jobId, $data);
            
            if ($updated) {
                $_SESSION['success'] = 'Job updated successfully!';
                header('Location: ' . BASE_URL . '/company/managejobs');
            } else {
                $_SESSION['error'] = 'Failed to update job. Please try again.';
                header('Location: ' . BASE_URL . '/company/postjobs?id=' . $jobId);
            }
            exit;
        }
        
        // If not POST request, redirect to managejobs page
        header('Location: ' . BASE_URL . '/company/managejobs');
        exit;
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jobId = $_POST['job_id'];
            
            // Delete job from database
            $deleted = $this->jobModel->delete($jobId);
            
            if ($deleted) {
                $_SESSION['success'] = 'Job deleted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to delete job. Please try again.';
            }
        }
        
        header('Location: ' . BASE_URL . '/company/managejobs');
        exit;
    }
    
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jobId = $_POST['job_id'];
            $status = $_POST['status'];
            
            // Update job status
            $updated = $this->jobModel->updateStatus($jobId, $status);
            
            if ($updated) {
                echo json_encode(['success' => true, 'message' => 'Status updated successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
            }
            exit;
        }
        
        header('Location: ' . BASE_URL . '/company/managejobs');
        exit;
    }
    
    public function managejobs(){
        // Get all jobs for the company
        $companyId = $_SESSION['user_id'] ?? 1; // Replace with actual session user ID
        $jobs = $this->jobModel->getJobsByCompany($companyId);
        
        $data['jobs'] = $jobs;
        
        $this->view('actors/company/managejobs', $data);
    }
    
    public function applications(){

        $this->view('actors/company/applications');
    }
    
    public function profile(){

        $this->view('actors/company/profile');
    }

    // Show single job details
    public function jobdetails() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '/company/managejobs');
            exit;
        }

        $job = $this->jobModel->findById($id);
        if (!$job) {
            $_SESSION['error'] = 'Job not found.';
            header('Location: ' . BASE_URL . '/company/managejobs');
            exit;
        }

        $data['job'] = $job;
        $this->view('actors/company/jobdetails', $data);
    }
}
