<?php

class Student_profile_model extends Model
{
    protected $table = 'users';

    public function getUserById(int $user_id)
    {
        $query = "SELECT 
                    user_id,
                    first_name,
                    middle_name,
                    last_name,
                    date_of_birth,
                    gender,
                    email,
                    phone,
                    address,
                    profile_picture
                  FROM {$this->table}
                  WHERE user_id = :user_id
                  LIMIT 1";

        return $this->fetch($query, ['user_id' => $user_id]);
    }

    /**
     * Update profile fields for a user.
     * Allowed keys in $data:
     * first_name, middle_name, last_name, date_of_birth, gender,
     * phone_number, address, profile_picture
     */
    public function updateUserProfile(int $user_id, array $data)
    {
        $allowed = [
            'first_name',
            'middle_name',
            'last_name',
            'date_of_birth',
            'gender',
            'phone',
            'address',
            'profile_picture',
        ];

        $setParts = [];
        $params = ['user_id' => $user_id];

        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $setParts[] = "{$field} = :{$field}";
                $params[$field] = $data[$field];
            }
        }

        // nothing to update
        if (empty($setParts)) {
            return true;
        }

        $query = "UPDATE {$this->table}
                  SET " . implode(', ', $setParts) . "
                  WHERE user_id = :user_id";

        return $this->query($query, $params);
    }

    public function getPasswordHashByUserId(int $user_id)
    {
        $query = "SELECT password_hash
                  FROM {$this->table}
                  WHERE user_id = :user_id
                  LIMIT 1";

        $row = $this->fetch($query, ['user_id' => $user_id]);

        return is_array($row) ? ($row['password_hash'] ?? null) : null;
    }

    public function updatePasswordHash(int $user_id, string $newHash)
    {
        $query = "UPDATE {$this->table}
                  SET password_hash = :password_hash
                  WHERE user_id = :user_id";

        $params = [
            'password_hash' => $newHash,
            'user_id' => $user_id,
        ];

        return $this->query($query, $params);
    }
}