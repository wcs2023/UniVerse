<?php

class JobApplication extends Model
{
    /**
     * Create a new job application
     */
    public function createApplication($data)
    {
        $query = "INSERT INTO job_applications (
                    job_id, user_id, cover_letter, resume_url, portfolio_url,
                    expected_salary, availability_date, status
                  ) VALUES (
                    :job_id, :user_id, :cover_letter, :resume_url, :portfolio_url,
                    :expected_salary, :availability_date, :status
                  )";
        
        $result = $this->query($query, [
            'job_id' => $data['job_id'],
            'user_id' => $data['user_id'],
            'cover_letter' => $data['cover_letter'] ?? null,
            'resume_url' => $data['resume_url'] ?? null,
            'portfolio_url' => $data['portfolio_url'] ?? null,
            'expected_salary' => $data['expected_salary'] ?? null,
            'availability_date' => $data['availability_date'] ?? null,
            'status' => $data['status'] ?? 'pending'
        ]);
        
        // Update applications count in jobs table
        if ($result) {
            $this->query("UPDATE jobs SET applications_count = applications_count + 1 WHERE job_id = :job_id", 
                ['job_id' => $data['job_id']]);
        }
        
        return $result ? $this->lastInsertId() : false;
    }

    /**
     * Get all applications for a specific job
     */
    public function getApplicationsByJob($jobId, $status = null)
    {
        $query = "SELECT ja.*, 
                        u.first_name, u.last_name, u.email, u.phone,
                        up.university, up.degree_program, up.academic_year,
                        j.title as job_title
                  FROM job_applications ja
                  INNER JOIN users u ON ja.user_id = u.user_id
                  LEFT JOIN undergraduate_profiles up ON ja.user_id = up.user_id
                  INNER JOIN jobs j ON ja.job_id = j.job_id
                  WHERE ja.job_id = :job_id";
        
        $params = ['job_id' => $jobId];
        
        if ($status) {
            $query .= " AND ja.status = :status";
            $params['status'] = $status;
        }
        
        $query .= " ORDER BY ja.applied_at DESC";
        
        return $this->fetchAll($query, $params);
    }

    /**
     * Get all applications by a company (across all their jobs)
     */
    public function getApplicationsByCompany($companyId, $filters = [])
    {
        $query = "SELECT ja.*, 
                        u.first_name, u.last_name, u.email, u.phone,
                        up.university, up.degree_program, up.academic_year,
                        j.title as job_title, j.job_id
                  FROM job_applications ja
                  INNER JOIN jobs j ON ja.job_id = j.job_id
                  INNER JOIN users u ON ja.user_id = u.user_id
                  LEFT JOIN undergraduate_profiles up ON ja.user_id = up.user_id
                  WHERE j.company_id = :company_id";
        
        $params = ['company_id' => $companyId];
        
        // Add filters
        if (!empty($filters['status'])) {
            $query .= " AND ja.status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['job_id'])) {
            $query .= " AND ja.job_id = :job_id";
            $params['job_id'] = $filters['job_id'];
        }
        
        $query .= " ORDER BY ja.applied_at DESC";
        
        return $this->fetchAll($query, $params);
    }

    /**
     * Verify that a given applicant has applied to a job owned by a company
     */
    public function companyHasApplicant($companyId, $applicantUserId)
    {
        $query = "SELECT 1
                  FROM job_applications ja
                  INNER JOIN jobs j ON ja.job_id = j.job_id
                  WHERE j.company_id = :company_id
                    AND ja.user_id = :user_id
                  LIMIT 1";

        $row = $this->fetch($query, [
            'company_id' => $companyId,
            'user_id' => $applicantUserId
        ]);

        return (bool)$row;
    }

    /**
     * Get application by ID
     */
    public function getApplicationById($applicationId)
    {
        $query = "SELECT ja.*, 
                        u.first_name, u.last_name, u.email, u.phone,
                        up.university, up.degree_program, up.academic_year,
                        j.title as job_title, j.company_id, j.description as job_description,
                        cp.company_name
                  FROM job_applications ja
                  INNER JOIN users u ON ja.user_id = u.user_id
                  LEFT JOIN undergraduate_profiles up ON ja.user_id = up.user_id
                  INNER JOIN jobs j ON ja.job_id = j.job_id
                  LEFT JOIN company_profiles cp ON j.company_id = cp.user_id
                  WHERE ja.application_id = :application_id
                  LIMIT 1";
        
        return $this->fetch($query, ['application_id' => $applicationId]);
    }

    /**
     * Get applications by user
     */
    public function getApplicationsByUser($userId)
    {
        $query = "SELECT ja.*, 
                        j.title as job_title, j.location, j.job_type,
                        cp.company_name, cp.industry
                  FROM job_applications ja
                  INNER JOIN jobs j ON ja.job_id = j.job_id
                  LEFT JOIN company_profiles cp ON j.company_id = cp.user_id
                  WHERE ja.user_id = :user_id
                  ORDER BY ja.applied_at DESC";
        
        return $this->fetchAll($query, ['user_id' => $userId]);
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus($applicationId, $status, $reviewedBy = null, $notes = null)
    {
        $query = "UPDATE job_applications SET 
                    status = :status,
                    reviewed_at = CURRENT_TIMESTAMP,
                    reviewed_by = :reviewed_by,
                    notes = :notes
                  WHERE application_id = :application_id";
        
        return $this->query($query, [
            'application_id' => $applicationId,
            'status' => $status,
            'reviewed_by' => $reviewedBy,
            'notes' => $notes
        ]);
    }

    /**
     * Check if user already applied for a job
     */
    public function hasUserApplied($jobId, $userId)
    {
        $query = "SELECT COUNT(*) as count FROM job_applications 
                  WHERE job_id = :job_id AND user_id = :user_id";
        $result = $this->fetch($query, [
            'job_id' => $jobId,
            'user_id' => $userId
        ]);
        return $result && $result['count'] > 0;
    }

    /**
     * Withdraw application
     */
    public function withdrawApplication($applicationId, $userId)
    {
        $query = "UPDATE job_applications SET status = 'withdrawn' 
                  WHERE application_id = :application_id AND user_id = :user_id";
        return $this->query($query, [
            'application_id' => $applicationId,
            'user_id' => $userId
        ]);
    }

    /**
     * Get application statistics for a job
     */
    public function getJobApplicationStats($jobId)
    {
        $query = "SELECT 
                    COUNT(*) as total_applications,
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending,
                    COUNT(CASE WHEN status = 'under_review' THEN 1 END) as under_review,
                    COUNT(CASE WHEN status = 'shortlisted' THEN 1 END) as shortlisted,
                    COUNT(CASE WHEN status = 'interviewed' THEN 1 END) as interviewed,
                    COUNT(CASE WHEN status = 'hired' THEN 1 END) as hired,
                    COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected
                  FROM job_applications
                  WHERE job_id = :job_id";
        
        return $this->fetch($query, ['job_id' => $jobId]);
    }

    /**
     * Delete application
     */
    public function deleteApplication($applicationId)
    {
        // First get the job_id to update the count
        $app = $this->fetch("SELECT job_id FROM job_applications WHERE application_id = :id", 
            ['id' => $applicationId]);
        
        if ($app) {
            // Delete the application
            $result = $this->query("DELETE FROM job_applications WHERE application_id = :id", 
                ['id' => $applicationId]);
            
            // Update applications count
            if ($result) {
                $this->query("UPDATE jobs SET applications_count = applications_count - 1 
                             WHERE job_id = :job_id AND applications_count > 0", 
                    ['job_id' => $app['job_id']]);
            }
            
            return $result;
        }
        
        return false;
    }
}
