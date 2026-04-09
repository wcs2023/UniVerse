<?php
// filepath: c:\xampp\htdocs\UniVerse\app\models\User.php

class User extends Model 
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
        try {
            $query = "SELECT * FROM users WHERE user_id = :id LIMIT 1";
            return $this->fetch($query, ['id' => $id]);
        } catch (Exception $e) {
            error_log("Error in getUserById(): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        try {
            $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
            return $this->fetch($query, ['email' => $email]);
        } catch (Exception $e) {
            error_log("Error in getUserByEmail(): " . $e->getMessage());
            return null;
        }
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
     * Get all users
     */
    public function getAllUsers()
    {
        try {
            $query = "SELECT 
                        user_id,
                        username,
                        email,
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
        } catch (Exception $e) {
            error_log("Error in getAllUsers(): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get users by type
     */
    public function getUsersByType($userType = null)
    {
        try {
            // If userType is null, 'all', or empty, return all users
            if (!$userType || $userType === 'all') {
                return $this->getAllUsers();
            }
            
            $query = "SELECT 
                        user_id,
                        username,
                        email,
                        first_name,
                        last_name,
                        user_type,
                        account_status,
                        email_verified,
                        phone,
                        created_at,
                        last_login
                      FROM users 
                      WHERE user_type = :user_type 
                      ORDER BY created_at DESC";
            return $this->fetchAll($query, ['user_type' => $userType]);
        } catch (Exception $e) {
            error_log("Error in getUsersByType(): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get total users count
     */
    public function getTotalUsersCount()
    {
        try {
            $query = "SELECT COUNT(*) as count FROM users";
            $result = $this->fetch($query);
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getTotalUsersCount(): " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get users count by type
     */
    public function getUsersCountByType($userType)
    {
        try {
            $query = "SELECT COUNT(*) as count FROM users WHERE user_type = :user_type";
            $result = $this->fetch($query, ['user_type' => $userType]);
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getUsersCountByType(): " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Update user
     */
    public function updateUser($userId, $data)
    {
        try {
            $setParts = [];
            $params = ['user_id' => $userId];
            
            foreach ($data as $key => $value) {
                $setParts[] = "$key = :$key";
                $params[$key] = $value;
            }
            
            $setClause = implode(', ', $setParts);
            $query = "UPDATE users SET $setClause, updated_at = NOW() WHERE user_id = :user_id";
            
            // Execute and return the statement to check rowCount
            $stmt = $this->query($query, $params);
            
            // Return true if at least one row was affected
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error in updateUser(): " . $e->getMessage());
            error_log("Query: " . ($query ?? 'N/A'));
            error_log("Params: " . print_r($params, true));
            return false;
        }
    }

    /**
     * Update user profile picture
     */
    public function updateProfilePicture($userId, $picturePath)
    {
        try {
            $query = "UPDATE users SET profile_picture = :profile_picture, updated_at = NOW() WHERE user_id = :user_id";
            $this->query($query, [
                'profile_picture' => $picturePath,
                'user_id' => $userId
            ]);
            return true;
        } catch (Exception $e) {
            error_log("Error in updateProfilePicture(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Activate user account
     */
    public function activateUser($userId)
    {
        try {
            $query = "UPDATE users SET 
                        account_status = 'active',
                        updated_at = NOW()
                      WHERE user_id = :user_id";
            
            return $this->query($query, ['user_id' => $userId]);
        } catch (Exception $e) {
            error_log("Error in activateUser(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deactivate user account
     */
    public function deactivateUser($userId)
    {
        try {
            $query = "UPDATE users SET 
                        account_status = 'inactive',
                        updated_at = NOW()
                      WHERE user_id = :user_id";
            
            return $this->query($query, ['user_id' => $userId]);
        } catch (Exception $e) {
            error_log("Error in deactivateUser(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete user account
     */
    public function deleteUser($userId)
    {
        try {
            $query = "DELETE FROM users WHERE user_id = :user_id";
            return $this->query($query, ['user_id' => $userId]);
        } catch (Exception $e) {
            error_log("Error in deleteUser(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeUserId = null)
    {
        try {
            if ($excludeUserId) {
                $query = "SELECT COUNT(*) as count FROM users WHERE email = :email AND user_id != :user_id";
                $result = $this->fetch($query, ['email' => $email, 'user_id' => $excludeUserId]);
            } else {
                $query = "SELECT COUNT(*) as count FROM users WHERE email = :email";
                $result = $this->fetch($query, ['email' => $email]);
            }
            return ($result['count'] ?? 0) > 0;
        } catch (Exception $e) {
            error_log("Error in emailExists(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update last login
     */
    public function updateLastLogin($userId)
    {
        try {
            $query = "UPDATE users SET last_login = NOW() WHERE user_id = :user_id";
            return $this->query($query, ['user_id' => $userId]);
        } catch (Exception $e) {
            error_log("Error in updateLastLogin(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create user (for registration)
     */
    public function createUser($data)
    {
        try {
            $query = "INSERT INTO users (
                        username, email, password_hash, first_name,last_name,
                        date_of_birth, gender, phone, user_type, account_status
                      ) VALUES (
                        :username, :email, :password_hash, :first_name, :last_name,
                        :date_of_birth, :gender, :phone, :user_type, 'active'
                      )";
            
            $result = $this->query($query, [
                'username' => $data['username'],
                'email' => $data['email'],
                'password_hash' => $data['password_hash'],
                'first_name' => $data['first_name'],
                // 'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'],
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? 'male',
                'phone' => $data['phone'] ?? null,
                'user_type' => $data['user_type'] ?? 'undergraduate'
            ]);
            
            if ($result) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            error_log("Error in createUser(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify user login
     */
    public function verifyLogin($email, $password)
    {
        $user = $this->getUserByEmail($email);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $this->updateLastLogin($user['user_id']);
            return $user;
        }
        
        return false;
    }

    /**
     * Search users
     */
    public function searchUsers($searchTerm, $userType = null)
    {
        try {
            if ($userType && $userType !== 'all') {
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
        } catch (Exception $e) {
            error_log("Error in searchUsers(): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update user password
     */
    public function updatePassword($userId, $hashedPassword)
    {
        try {
            $query = "UPDATE users SET 
                        password_hash = :password_hash,
                        updated_at = NOW()
                      WHERE user_id = :user_id";
            
            $stmt = $this->query($query, [
                'password_hash' => $hashedPassword,
                'user_id' => $userId
            ]);
            
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error in updatePassword(): " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteByUserId($userId)
    {
        try {
            return $this->delete('Articles', 'user_id = :user_id', ['user_id' => $userId]);
        } catch (Exception $e) {
            error_log("Error deleting articles by user: " . $e->getMessage());
            return false;
        }
    }
}
