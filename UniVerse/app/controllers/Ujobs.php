<?php

class Ujobs extends Controller
{
    private $jobModel;

    public function __construct()
    {
        $this->jobModel = $this->model('Job');
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
            redirect('ujobs');
        }

        // Get job details
        $job = $this->jobModel->getJobById($jobId);

        if (!$job) {
            redirect('ujobs');
        }

        // Increment views count
        $this->jobModel->incrementViews($jobId);

        $data = [
            'job' => $job
        ];

        $this->view('actors/undergraduate/UjobDetails', $data);
    }

    /**
     * Apply for a job
     */
    public function apply($jobId)
    {
        if (!$jobId) {
            redirect('ujobs');
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $job = $this->jobModel->getJobById($jobId);

        if (!$job) {
            redirect('ujobs');
        }

        $data = [
            'job' => $job
        ];

        $this->view('actors/undergraduate/UapplyJob', $data);
    }
}