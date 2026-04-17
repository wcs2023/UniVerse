<?php
class ContactMessage extends Model
{
    public function createMessage($data)
    {
        $query = "INSERT INTO contact_messages (
                    user_id, name, email, subject, message, status
                  ) VALUES (
                    :user_id, :name, :email, :subject, :message, :status
                  )";

        return $this->query($query, [
            'user_id'  => $data['user_id'] ?? null,
            'name'     => $data['name'],
            'email'    => $data['email'],
            'subject'  => $data['subject'],
            'message'  => $data['message'],
            'status'   => $data['status'] ?? 'new'
        ]);
    }

    public function getAllMessages($filters = [])
    {
        $query = "SELECT cm.*
                  FROM contact_messages cm
                  WHERE 1=1";

        $params = [];

        if (!empty($filters['search'])) {
            $query .= " AND (
                cm.name LIKE :search_name
                OR cm.email LIKE :search_email
                OR cm.subject LIKE :search_subject
                OR cm.message LIKE :search_message
                OR CAST(cm.user_id AS CHAR) LIKE :search_user_id
            )";

            $searchTerm = '%' . trim($filters['search']) . '%';
            $params['search_name'] = $searchTerm;
            $params['search_email'] = $searchTerm;
            $params['search_subject'] = $searchTerm;
            $params['search_message'] = $searchTerm;
            $params['search_user_id'] = $searchTerm;
        }

        // if (!empty($filters['status'])) {
        //     $query .= " AND cm.status = :status";
        //     $params['status'] = $filters['status'];
        // }

        $query .= " ORDER BY cm.created_at DESC";

        return $this->fetchAll($query, $params);
    }

    public function getMessageById($id)
    {
        $query = "SELECT cm.*
                  FROM contact_messages cm
                  WHERE cm.contact_id = :id
                  LIMIT 1";

        return $this->fetch($query, ['id' => $id]);
    }

    public function markAsRead($id)
    {
        $query = "UPDATE contact_messages
                  SET status = 'read'
                  WHERE contact_id = :id";

        return $this->query($query, ['id' => $id]);
    }

    // public function updateStatus($id, $status)
    // {
    //     $query = "UPDATE contact_messages
    //               SET status = :status
    //               WHERE contact_id = :id";

    //     return $this->query($query, [
    //         'id' => $id,
    //         'status' => $status
    //     ]);
    // }

    public function deleteMessage($id)
    {
        $query = "DELETE FROM contact_messages WHERE contact_id = :id";
        return $this->query($query, ['id' => $id]);
    }
}