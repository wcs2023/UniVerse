<?php

class UserModel extends Model 
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        $query = "SELECT * FROM users WHERE user_id = :id";
        return $this->fetch($query, ['id' => $id]);
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        return $this->fetch($query, ['email' => $email]);
    }

    /**
     * Get user by username
     */
    public function getUserByUsername($username)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        return $this->fetch($query, ['username' => $username]);
    }

    /**
     * Create a new user
     */
    public function createUser($data)
    {
        $query = "INSERT INTO users (
                    username, email, password_hash, first_name, middle_name, last_name,
                    date_of_birth, gender, phone, user_type
                  ) VALUES (
                    :username, :email, :password_hash, :first_name, :middle_name, :last_name,
                    :date_of_birth, :gender, :phone, :user_type
                  )";
        
        $result = $this->query($query, [
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'], // Already hashed in controller
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? 'male',
            'phone' => $data['phone'] ?? null,
            'user_type' => $data['user_type'] ?? 'undergraduate'
        ]);
        
        if ($result) {
            return $this->lastInsertId();
        }
        return false;
    }

    /**
     * Update user profile
     */
    public function updateUser($id, $data)
    {
        $query = "UPDATE users SET 
                    first_name = :first_name,
                    middle_name = :middle_name,
                    last_name = :last_name,
                    date_of_birth = :date_of_birth,
                    gender = :gender,
                    phone = :phone,
                    address_line1 = :address_line1,
                    address_line2 = :address_line2,
                    city = :city,
                    province = :province,
                    updated_at = NOW()
                  WHERE user_id = :id";
        
        return $this->query($query, [
            'id' => $id,
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? 'male',
            'phone' => $data['phone'] ?? null,
            'address_line1' => $data['address_line1'] ?? null,
            'address_line2' => $data['address_line2'] ?? null,
            'city' => $data['city'] ?? null,
            'province' => $data['province'] ?? null
        ]);
    }

    /**
     * Update profile picture
     */
    public function updateProfilePicture($userId, $profilePicturePath)
    {
        $query = "UPDATE users SET profile_picture = :profile_picture, updated_at = NOW() WHERE user_id = :id";
        return $this->query($query, [
            'id' => $userId,
            'profile_picture' => $profilePicturePath
        ]);
    }

    /**
     * Verify user login credentials
     */
    public function verifyLogin($email, $password)
    {
        $user = $this->getUserByEmail($email);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Update last login time
            $this->updateLastLogin($user['id']);
            return $user;
        }
        
        return false;
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin($userId)
    {
        $query = "UPDATE users SET last_login = NOW() WHERE user_id = :id";
        return $this->query($query, ['id' => $userId]);
    }

    /**
     * Get all users by type
     */
    public function getUsersByType($userType)
    {
        $query = "SELECT * FROM users WHERE user_type = :user_type ORDER BY created_at DESC";
        return $this->fetchAll($query, ['user_type' => $userType]);
    }

    /**
     * Search users
     */
    public function searchUsers($searchTerm, $userType = null)
    {
        if ($userType) {
            $query = "SELECT * FROM users 
                     WHERE (first_name LIKE :search OR last_name LIKE :search OR email LIKE :search OR username LIKE :search)
                     AND user_type = :user_type
                     ORDER BY first_name, last_name";
            
            return $this->fetchAll($query, [
                'search' => "%$searchTerm%",
                'user_type' => $userType
            ]);
        } else {
            $query = "SELECT * FROM users 
                     WHERE first_name LIKE :search OR last_name LIKE :search OR email LIKE :search OR username LIKE :search
                     ORDER BY first_name, last_name";
            
            return $this->fetchAll($query, ['search' => "%$searchTerm%"]);
        }
    }

    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeUserId = null)
    {
        if ($excludeUserId) {
            $query = "SELECT COUNT(*) as count FROM users WHERE email = :email AND user_id != :exclude_id";
            $result = $this->fetch($query, ['email' => $email, 'exclude_id' => $excludeUserId]);
        } else {
            $query = "SELECT COUNT(*) as count FROM users WHERE email = :email";
            $result = $this->fetch($query, ['email' => $email]);
        }
        
        return $result['count'] > 0;
    }

    /**
     * Check if username exists
     */
    public function usernameExists($username, $excludeUserId = null)
    {
        if ($excludeUserId) {
            $query = "SELECT COUNT(*) as count FROM users WHERE username = :username AND user_id != :exclude_id";
            $result = $this->fetch($query, ['username' => $username, 'exclude_id' => $excludeUserId]);
        } else {
            $query = "SELECT COUNT(*) as count FROM users WHERE username = :username";
            $result = $this->fetch($query, ['username' => $username]);
        }
        
        return $result['count'] > 0;
    }

    /**
     * Get user statistics
     */
    public function getUserStats()
    {
        $query = "SELECT 
                    user_type,
                    COUNT(*) as count,
                    COUNT(CASE WHEN account_status = 'active' THEN 1 END) as active_count
                  FROM users 
                  GROUP BY user_type";
        
        return $this->fetchAll($query);
    }

    /**
     * Update user password
     */
    public function updatePassword($userId, $hashedPassword) {
        $sql = "UPDATE users SET password = :password WHERE user_id = :user_id";
        return $this->query($sql, [
            'user_id' => $userId,
            'password' => $hashedPassword
        ]);
    }

    // ==================== ADMIN METHODS ====================

    /**
     * Get all users (for admin panel)
     */
    public function getAllUsers()
    {
        $query = "SELECT 
                    user_id,
                    username,
                    email,
                    CONCAT(first_name, ' ', last_name) as full_name,
                    first_name,
                    last_name,
                    user_type,
                    account_status,
                    email_verified,
                    phone,
                    created_at,
                    last_login
                  FROM users 
                  ORDER BY created_at DESC";
        
        return $this->fetchAll($query);
    }

    /**
     * Get pending registrations (users with inactive status)
     */
    public function getPendingRegistrations()
    {
        $query = "SELECT 
                    user_id,
                    username,
                    email,
                    CONCAT(first_name, ' ', last_name) as full_name,
                    user_type,
                    phone,
                    created_at
                  FROM users 
                  WHERE account_status = 'inactive' OR account_status = 'pending'
                  ORDER BY created_at DESC";
        
        return $this->fetchAll($query);
    }

    /**
     * Get total users count
     */
    public function getTotalUsersCount()
    {
        $query = "SELECT COUNT(*) as count FROM users";
        $result = $this->fetch($query);
        return $result['count'] ?? 0;
    }

    /**
     * Get pending registrations count
     */
    public function getPendingRegistrationsCount()
    {
        $query = "SELECT COUNT(*) as count FROM users WHERE account_status = 'inactive' OR account_status = 'pending'";
        $result = $this->fetch($query);
        return $result['count'] ?? 0;
    }

    /**
     * Activate user account
     */
    public function activateUser($userId)
    {
        $query = "UPDATE users SET 
                    account_status = 'active',
                    email_verified = 1,
                    updated_at = NOW()
                  WHERE user_id = :id";
        
        return $this->query($query, ['id' => $userId]);
    }

    /**
     * Deactivate user account
     */
    public function deactivateUser($userId)
    {
        $query = "UPDATE users SET 
                    account_status = 'inactive',
                    updated_at = NOW()
                  WHERE user_id = :id";
        
        return $this->query($query, ['id' => $userId]);
    }

    /**
     * Delete user account
     */
    public function deleteUser($userId)
    {
        $query = "DELETE FROM users WHERE user_id = :id";
        return $this->query($query, ['id' => $userId]);
    }

    /**
     * Approve registration
     */
    public function approveRegistration($userId)
    {
        return $this->activateUser($userId);
    }

    /**
     * Reject registration (delete user)
     */
    public function rejectRegistration($userId)
    {
        return $this->deleteUser($userId);
    }

    /**
     * Get users by status
     */
    public function getUsersByStatus($status)
    {
        $query = "SELECT 
                    user_id,
                    username,
                    email,
                    CONCAT(first_name, ' ', last_name) as full_name,
                    user_type,
                    account_status,
                    created_at
                  FROM users 
                  WHERE account_status = :status
                  ORDER BY created_at DESC";
        
        return $this->fetchAll($query, ['status' => $status]);
    }

    /**
     * Update user status
     */
    public function updateUserStatus($userId, $status)
    {
        $query = "UPDATE users SET 
                    account_status = :status,
                    updated_at = NOW()
                  WHERE user_id = :id";
        
        return $this->query($query, [
            'id' => $userId,
            'status' => $status
        ]);
    }
}

