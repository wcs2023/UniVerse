<?php
class Forum_post_model extends Model
{
    protected $table = 'forum_posts';

    public function getByThread($thread_id)
    {
        $query = "SELECT 
                    fp.*,
                    u.first_name AS author_fname,
                    u.last_name AS author_lname,
                    u.user_id AS author_id
                  FROM {$this->table} fp 
                  JOIN users u ON fp.user_id = u.user_id
                  WHERE fp.thread_id = :thread_id
                  ORDER BY fp.created_at ASC";

        $params = ['thread_id' => $thread_id];

        $result =  $this->fetchAll($query, $params);

        return $result ?: [];
    }

    public function create_reply($data)
    {
        $query = "INSERT INTO {$this->table} (thread_id,user_id,content) 
        VALUES (:thread_id,:user_id,:content) ";

        $params = [
            'thread_id' => $data['thread_id'],
            'user_id' => $data['user_id'],
            'content' => $data['content']
        ];

        return $this->query($query, $params);
    }

    public function getPostDetailsWithId($post_id)
    {
        $query = "SELECT fp.*,
                    u.first_name AS author_fname,
                    u.last_name AS author_lname,
                    u.user_id as author_id
                    FROM{$this->table} fp
                    JOIN users u ON fp.user_id = u.user_id
                    WHERE fp.post_id = :post_id
                    ";

        return $this->fetch($query, ['post_id' => $post_id]);
    }

    public function update_reply($post_id, $content, $user_id)
    {
        $query = "UPDATE {$this->table} 
                    SET content = :content,
                        is_edited = 1,
                        edited_at = NOW()
                    WHERE post_id = :post_id AND user_id = :user_id";

        return $this->query($query, ['post_id' => $post_id, 'content' => $content, 'user_id' => $user_id]);
    }

    public function delete_a_single_reply($post_id)
    {
        $query = "DELETE FROM {$this->table} WHERE post_id = :post_id";

        return $this->query($query, ['post_id' => $post_id]);
    }

    public function delete_all_reply($thread_id)
    {
        $query = "DELETE FROM {$this->table} WHERE thread_id = :thread_id";

        return $this->query($query, ['thread_id' => $thread_id]);
    }
}
