<?php

class Job extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'jobs';
    }
    
    // Get all jobs for a specific company
    public function getJobsByCompany($companyId) {
        $sql = "SELECT * FROM {$this->table} WHERE company_id = :company_id ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['company_id' => $companyId]);
    }
    
    // Get active jobs
    public function getActiveJobs($companyId = null) {
        if ($companyId) {
            $sql = "SELECT * FROM {$this->table} WHERE status = 'active' AND company_id = :company_id ORDER BY created_at DESC";
            return $this->db->fetchAll($sql, ['company_id' => $companyId]);
        } else {
            $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY created_at DESC";
            return $this->db->fetchAll($sql);
        }
    }
    
    // Search jobs
    public function searchJobs($keyword, $companyId = null) {
        if ($companyId) {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE (job_title LIKE :keyword OR description LIKE :keyword OR requirements LIKE :keyword)
                    AND company_id = :company_id
                    ORDER BY created_at DESC";
            return $this->db->fetchAll($sql, [
                'keyword' => "%{$keyword}%",
                'company_id' => $companyId
            ]);
        } else {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE job_title LIKE :keyword OR description LIKE :keyword OR requirements LIKE :keyword
                    ORDER BY created_at DESC";
            return $this->db->fetchAll($sql, ['keyword' => "%{$keyword}%"]);
        }
    }
    
    // Update job status
    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->db->query($sql, ['status' => $status, 'id' => $id]);
        return $stmt->rowCount();
    }
    
    // Count applications for a job (you'll need to create an applications table later)
    public function countApplications($jobId) {
        // Placeholder - you can implement this when you create the applications table
        return 0;
    }

    public function countActiveByCompany($companyId) {
        $sql = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE company_id = :cid AND status = 'active'";
        $row = $this->db->fetchOne($sql, ['cid' => $companyId]);
        return (int)($row->cnt ?? 0);
    }

    public function recentActivity($companyId, $limit = 5) {
        $sql = "SELECT id, job_title, status, created_at, updated_at
                FROM {$this->table}
                WHERE company_id = :cid
                ORDER BY GREATEST(created_at, updated_at) DESC
                LIMIT {$limit}";
        return $this->db->fetchAll($sql, ['cid' => $companyId]);
    }
}
