<?php
class ResetModel extends Model
{   
    public function __construct()
    {
        return parent::__construct();
    }

    public function insertOTP($data)
    {   
        $sql = "INSERT INTO otp_tokens (email,otp_hash,expires_at)
                    VALUES (:email,:otp_hash,:expires_at)";
        $result = $this->query($sql,
        [
            'email' => $data['email'],
            'otp_hash' => $data['otp_hash'],
            'expires_at' => $data['expires_at']
        ]);

        return $result;
    }

    public function updateUserPassword($email, $hashed_password)
    {
        // Make sure the table name 'users' and column 'password_hash' 
        // match your database exactly.
        $sql = "UPDATE users SET password_hash = :password WHERE email = :email";
        
        return $this->query($sql, [
            'password' => $hashed_password,
            'email' => $email
        ]);
    }

    public function markOTPUsed($email)
    {
        $sql = "UPDATE otp_tokens
            SET is_used = 1
            WHERE email = :email
            AND is_used = 0
            AND expires_at > NOW()";

        return $this->query($sql, ['email' => $email]);
    }
    public function emailExists($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        return $this->first($sql, ['email' => $email]);
    }      
    
        public function getLatestValidOTP($email)
    {
        $sql = "SELECT * FROM otp_tokens where email=:email AND is_used=0
                AND expires_at > NOW() ORDER BY created_at DESC
                LIMIT 1";

        return $this->first($sql,['email'=>$email]);
    }
}