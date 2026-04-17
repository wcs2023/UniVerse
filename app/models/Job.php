<?php

class Job extends Model
{
    /**
     * Create a new job posting
     */
    public function createJob($data)
    {
        $query = "INSERT INTO jobs (
                    company_id, title, description, requirements, responsibilities,
                    location, job_type, experience_level, salary_min, salary_max,
                    currency, application_deadline, skills_required, benefits,
                    work_arrangement, contact_email, contact_phone, application_url,
                    status
                  ) VALUES (
                    :company_id, :title, :description, :requirements, :responsibilities,
                    :location, :job_type, :experience_level, :salary_min, :salary_max,
                    :currency, :application_deadline, :skills_required, :benefits,
                    :work_arrangement, :contact_email, :contact_phone, :application_url,
                    :status
                  )";
        
        $result = $this->query($query, [
            'company_id' => $data['company_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'requirements' => $data['requirements'] ?? null,
            'responsibilities' => $data['responsibilities'],
            'location' => $data['location'] ?? null,
            'job_type' => $data['job_type'] ?? 'full-time',
            'experience_level' => $data['experience_level'] ?? 'entry',
            'salary_min' => $data['salary_min'] ?? null,
            'salary_max' => $data['salary_max'] ?? null,
            'currency' => $data['currency'] ?? 'LKR',
            'application_deadline' => $data['application_deadline'] ?? null,
            'skills_required' => $data['skills_required'] ?? null,
            'benefits' => $data['benefits'] ?? null,
            'work_arrangement' => $data['work_arrangement'] ?? 'onsite',
            'contact_email' => $data['contact_email'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null,
            'application_url' => $data['application_url'] ?? null,
            'status' => $data['status'] ?? 'active'
        ]);
        
        return $result ? $this->lastInsertId() : false;
    }

    /**
     * Get all jobs by company ID
     */
    // public function getJobsByCompany($companyId, $status = null)
    // {
    //     if ($status) {
    //         $query = "SELECT j.*, 
    //                         COUNT(DISTINCT ja.application_id) as total_applications,
    //                         u.first_name, u.last_name
    //                   FROM jobs j
    //                   LEFT JOIN job_applications ja ON j.job_id = ja.job_id
    //                   LEFT JOIN users u ON j.company_id = u.user_id
    //                   WHERE j.company_id = :company_id AND j.status = :status
    //                   GROUP BY j.job_id
    //                   ORDER BY j.created_at DESC";
    //         return $this->fetchAll($query, [
    //             'company_id' => $companyId,
    //             'status' => $status
    //         ]);
    //     } else {
    //         $query = "SELECT j.*, 
    //                         COUNT(DISTINCT ja.application_id) as total_applications,
    //                         u.first_name, u.last_name
    //                   FROM jobs j
    //                   LEFT JOIN job_applications ja ON j.job_id = ja.job_id
    //                   LEFT JOIN users u ON j.company_id = u.user_id
    //                   WHERE j.company_id = :company_id
    //                   GROUP BY j.job_id
    //                   ORDER BY j.created_at DESC";
    //         return $this->fetchAll($query, ['company_id' => $companyId]);
    //     }
    // }
    public function getJobsByCompany($companyId, $status = null, $search = null)
    {
        $query = "SELECT j.*, 
                        COUNT(DISTINCT ja.application_id) as applications_count,
                        u.first_name, u.last_name
                FROM jobs j
                LEFT JOIN job_applications ja ON j.job_id = ja.job_id
                LEFT JOIN users u ON j.company_id = u.user_id
                WHERE j.company_id = :company_id";

        $params = [
            'company_id' => $companyId
        ];

        if (!empty($status)) {
            $query .= " AND j.status = :status";
            $params['status'] = $status;
        }

        if (!empty($search)) {
            $query .= " AND (
                j.title LIKE :search_title
                OR j.location LIKE :search_location
                OR j.job_type LIKE :search_job_type
                OR j.experience_level LIKE :search_experience
                OR j.status LIKE :search_status
            )";

            $searchTerm = '%' . $search . '%';
            $params['search_title'] = $searchTerm;
            $params['search_location'] = $searchTerm;
            $params['search_job_type'] = $searchTerm;
            $params['search_experience'] = $searchTerm;
            $params['search_status'] = $searchTerm;
        }

        $query .= " GROUP BY j.job_id
                    ORDER BY j.created_at DESC";

        return $this->fetchAll($query, $params);
    }

    public function updateJobStatus($jobId, $status)
    {
        $query = "UPDATE jobs SET status = :status WHERE job_id = :job_id";
        return $this->query($query, [
            'job_id' => $jobId,
            'status' => $status
        ]);
    }
    
    /**
     * Get job by ID
     */
    public function getJobById($jobId)
    {
        $query = "SELECT j.*, 
                        u.first_name, u.last_name,
                        cp.company_name, cp.industry, cp.website
                  FROM jobs j
                  LEFT JOIN users u ON j.company_id = u.user_id
                  LEFT JOIN company_profiles cp ON j.company_id = cp.user_id
                  WHERE j.job_id = :job_id
                  LIMIT 1";
        return $this->fetch($query, ['job_id' => $jobId]);
    }

    /**
     * Update job
     */
    public function updateJob($jobId, $data)
    {
        $query = "UPDATE jobs SET 
                    title = :title,
                    description = :description,
                    requirements = :requirements,
                    responsibilities = :responsibilities,
                    location = :location,
                    job_type = :job_type,
                    experience_level = :experience_level,
                    salary_min = :salary_min,
                    salary_max = :salary_max,
                    application_deadline = :application_deadline,
                    skills_required = :skills_required,
                    benefits = :benefits,
                    work_arrangement = :work_arrangement,
                    contact_email = :contact_email,
                    contact_phone = :contact_phone,
                    status = :status
                  WHERE job_id = :job_id";
        
        return $this->query($query, [
            'job_id' => $jobId,
            'title' => $data['title'],
            'description' => $data['description'],
            'requirements' => $data['requirements'] ?? null,
            'responsibilities' => $data['responsibilities'],
            'location' => $data['location'] ?? null,
            'job_type' => $data['job_type'] ?? 'full-time',
            'experience_level' => $data['experience_level'] ?? 'entry',
            'salary_min' => $data['salary_min'] ?? null,
            'salary_max' => $data['salary_max'] ?? null,
            'application_deadline' => $data['application_deadline'] ?? null,
            'skills_required' => $data['skills_required'] ?? null,
            'benefits' => $data['benefits'] ?? null,
            'work_arrangement' => $data['work_arrangement'] ?? 'onsite',
            'contact_email' => $data['contact_email'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null,
            'status' => $data['status'] ?? 'active'
        ]);
    }

    /**
     * Delete job
     */
    public function deleteJob($jobId)
    {
        $query = "DELETE FROM jobs WHERE job_id = :job_id";
        return $this->query($query, ['job_id' => $jobId]);
    }

    /**
     * Get all active jobs (for public listing)
     */
    public function getAllActiveJobs($filters = [])
    {
        $query = "SELECT j.*, 
                        cp.company_name, cp.industry,
                        u.first_name, u.last_name,
                        COUNT(DISTINCT ja.application_id) as total_applications
                  FROM jobs j
                  LEFT JOIN company_profiles cp ON j.company_id = cp.user_id
                  LEFT JOIN users u ON j.company_id = u.user_id
                  LEFT JOIN job_applications ja ON j.job_id = ja.job_id
                  WHERE j.status = 'active'";
        
        $params = [];
        
        // Add filters
        if (!empty($filters['job_type'])) {
            $query .= " AND j.job_type = :job_type";
            $params['job_type'] = $filters['job_type'];
        }
        
        if (!empty($filters['experience_level'])) {
            $query .= " AND j.experience_level = :experience_level";
            $params['experience_level'] = $filters['experience_level'];
        }
        
        if (!empty($filters['location'])) {
            $query .= " AND j.location LIKE :location";
            $params['location'] = '%' . $filters['location'] . '%';
        }
        
        $query .= " GROUP BY j.job_id ORDER BY j.created_at DESC";
        
        return $this->fetchAll($query, $params);
    }

    /**
     * Increment views count
     */
    public function incrementViews($jobId)
    {
        $query = "UPDATE jobs SET views_count = views_count + 1 WHERE job_id = :job_id";
        return $this->query($query, ['job_id' => $jobId]);
    }

    /**
     * Get job statistics for company
     */
    public function getCompanyJobStats($companyId)
    {
        $query = "SELECT 
                    COUNT(DISTINCT j.job_id) as total_jobs,
                    COUNT(DISTINCT CASE WHEN j.status = 'active' THEN j.job_id END) as active_jobs,
                    COUNT(DISTINCT ja.application_id) as total_applications,
                    COUNT(DISTINCT CASE WHEN ja.status = 'pending' THEN ja.application_id END) as pending_applications,
                    SUM(j.views_count) as total_views
                  FROM jobs j
                  LEFT JOIN job_applications ja ON j.job_id = ja.job_id
                  WHERE j.company_id = :company_id";
        
        return $this->fetch($query, ['company_id' => $companyId]);
    }
}
