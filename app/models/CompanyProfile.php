<?php

class CompanyProfile extends Model
{
    /**
     * Create company profile
     */
    public function createProfile($data)
    {
        $query = "INSERT INTO company_profiles (
                        user_id, company_name, company_size, company_description, 
                    contact_person, contact_email, contact_phone
                  ) VALUES (
                    :user_id, :company_name, :company_size,:company_description, 
                    :contact_person, :contact_email, :contact_phone
                  )";
        
        $result = $this->query($query, [
            'user_id' => $data['user_id'],
            'company_name' => $data['company_name'] ?? null,
            'company_size' => $data['company_size'] ?? null,
            'company_description' => $data['company_description'] ?? null,
            'contact_person' => $data['contact_person'] ?? null,
            'contact_email' => $data['contact_email'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null
        ]);
        
        return $result ? $this->lastInsertId() : false;
    }

    /**
     * Get company profile by user ID
     */
    public function getProfileByUserId($userId)
    {
        $query = "SELECT * FROM company_profiles WHERE user_id = :user_id LIMIT 1";
        $result = $this->fetch($query, ['user_id' => $userId]);
        return $result ? $result : null;
    }

    /**
     * Update company profile
     */
    public function updateProfile($userId, $data)
    {
        $query = "UPDATE company_profiles SET 
                    company_name = :company_name,
                    company_size = :company_size,
                    industry = :industry,
                    website = :website,
                    founded_year = :founded_year,
                    company_description = :company_description,
                    contact_person = :contact_person,
                    contact_email = :contact_email,
                    contact_phone = :contact_phone";
        
        // Add logo_url only if provided
        if (isset($data['logo_url'])) {
            $query .= ", logo_url = :logo_url";
        }
        
        $query .= " WHERE user_id = :user_id";
        
        $params = [
            'user_id' => $userId,
            'company_name' => $data['company_name'] ?? null,
            'company_size' => $data['company_size'] ?? null,
            'industry' => $data['industry'] ?? null,
            'website' => $data['website'] ?? null,
            'founded_year' => $data['founded_year'] ?? null,
            'company_description' => $data['company_description'] ?? null,
            'contact_person' => $data['contact_person'] ?? null,
            'contact_email' => $data['contact_email'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null
        ];
        
        if (isset($data['logo_url'])) {
            $params['logo_url'] = $data['logo_url'];
        }
        
        return $this->query($query, $params);
    }

    /**
     * Update only the company logo URL
     */
    public function updateLogoUrl($userId, $logoUrl)
    {
        $query = "UPDATE company_profiles SET logo_url = :logo_url WHERE user_id = :user_id";
        return $this->query($query, [
            'user_id' => $userId,
            'logo_url' => $logoUrl
        ]);
    }

    /**
     * Delete company profile
     */
    public function deleteProfile($userId)
    {
        $query = "DELETE FROM company_profiles WHERE user_id = :user_id";
        return $this->query($query, ['user_id' => $userId]);
    }

    /**
     * Verify company profile
     */
    public function verifyCompany($userId)
    {
        $query = "UPDATE company_profiles SET 
                    is_verified = TRUE,
                    verification_date = CURRENT_TIMESTAMP
                  WHERE user_id = :user_id";
        return $this->query($query, ['user_id' => $userId]);
    }

    /**
     * Get all verified companies
     */
    public function getVerifiedCompanies()
    {
        $query = "SELECT cp.*, u.username, u.email 
                  FROM company_profiles cp
                  JOIN users u ON cp.user_id = u.user_id
                  WHERE cp.is_verified = TRUE
                  ORDER BY cp.created_at DESC";
        return $this->query($query);
    }
}
