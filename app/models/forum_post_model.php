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

    public function getForumsWithType()
    {
        $query = "SELECT c.name AS category, COUNT(t.thread_id) AS count
                  FROM forum_categories c
                  LEFT JOIN forum_threads t ON c.cat_id = t.cat_id
                  GROUP BY c.cat_id";

        return $this->fetchAll($query);
    }

    public function getAllPostsForAdmin($statusFilter = 'all', $search = '') 
    {
        try 
        {
            $query = "SELECT
                        p.post_id       AS id,
                        p.thread_id,
                        p.user_id,
                        p.content       AS body,
                        p.is_deleted,
                        p.created_at,
                        p.updated_at,
                        t.title         AS thread_title,
                        c.name          AS category_name,
                        u.username,
                        u.email,
                        CONCAT(u.first_name, ' ', u.last_name) AS author_name
                    FROM forum_posts p
                    LEFT JOIN forum_threads t    ON t.thread_id = p.thread_id
                    LEFT JOIN forum_categories c ON c.cat_id    = t.cat_id
                    LEFT JOIN users u            ON u.user_id   = p.user_id
                    WHERE 1=1";

            $params = [];

            if ($statusFilter === 'hidden') {
                $query .= " AND p.is_deleted = 1";
            } elseif ($statusFilter === 'active') {
                $query .= " AND p.is_deleted = 0";
            }

            if (!empty($search)) {
                $query .= " AND (
                    t.title    LIKE :search1 OR
                    p.content  LIKE :search2 OR
                    u.username LIKE :search3 OR
                    u.email    LIKE :search4
                )";
                $params['search1'] = '%' . $search . '%';
                $params['search2'] = '%' . $search . '%';
                $params['search3'] = '%' . $search . '%';
                $params['search4'] = '%' . $search . '%';
            }

            $query .= " ORDER BY p.created_at DESC";

            return $this->fetchAll($query, $params);
        } 
        catch (Exception $e) 
        {
            error_log('Error getting forum posts for admin: ' . $e->getMessage());
            return [];
        }
    }

    public function getPostByIdForAdmin($postId) 
    {
        try 
        {
            $query = "SELECT
                    p.post_id       AS id,
                    p.thread_id,
                    p.user_id,
                    p.content       AS body,
                    p.is_deleted,
                    p.created_at,
                    p.updated_at,
                    t.title         AS thread_title,
                    c.name          AS category_name,
                    u.username,
                    u.email,
                    CONCAT(u.first_name, ' ', u.last_name) AS author_name
                  FROM forum_posts p
                  LEFT JOIN forum_threads t    ON t.thread_id = p.thread_id
                  LEFT JOIN forum_categories c ON c.cat_id    = t.cat_id
                  LEFT JOIN users u            ON u.user_id   = p.user_id
                  WHERE p.post_id = :id
                  LIMIT 1";

            return $this->fetch($query, ['id' => $postId]);
        } 
        catch (Exception $e) 
        {
            error_log('Error getting forum post by id for admin: ' . $e->getMessage());
            return null;
        }
    }

    public function hidePost($postId) 
    {
        try 
        {
            $this->query(
                "UPDATE forum_posts SET is_deleted = 1, updated_at = NOW() WHERE post_id = :id",
                ['id' => $postId]
            );
            return true;
        }
        catch (Exception $e) 
        {
            error_log('Error hiding forum post: ' . $e->getMessage());
            return false;
        }
    }

    public function unhidePost($postId) 
    {
        try 
        {
            $this->query(
                "UPDATE forum_posts SET is_deleted = 0, updated_at = NOW() WHERE post_id = :id",
                ['id' => $postId]
            );
            return true;
        } 
        catch (Exception $e) 
        {
            error_log('Error unhiding forum post: ' . $e->getMessage());
            return false;
        }
    }

    public function deletePostPermanently($postId) 
    {
        try 
        {
            $this->query(
                "DELETE FROM forum_posts WHERE post_id = :id",
                ['id' => $postId]
            );
            return true;
        }
        catch (Exception $e) 
        {
            error_log('Error deleting forum post permanently: ' . $e->getMessage());
            return false;
        }
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
                    FROM {$this->table} fp
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

    public function getReplyVotes(int $post_id)
{
    $query = "SELECT
                SUM(CASE WHEN vote = 1 THEN 1 ELSE 0 END) AS likes,
                SUM(CASE WHEN vote = -1 THEN 1 ELSE 0 END) AS dislikes
              FROM forum_post_votes
              WHERE post_id = :post_id";

    $row = $this->fetch($query, ['post_id' => $post_id]);

    return [
        'likes' => (int)($row['likes'] ?? 0),
        'dislikes' => (int)($row['dislikes'] ?? 0)
    ];
}

public function setReplyVote(int $post_id, int $user_id, int $vote)
{
    $vote = ($vote === -1) ? -1 : 1;

    $existing = $this->fetch(
        "SELECT vote FROM forum_post_votes WHERE post_id = :post_id AND user_id = :user_id",
        ['post_id' => $post_id, 'user_id' => $user_id]
    );

    if (is_array($existing) && (int)$existing['vote'] === $vote) {
        $this->query(
            "DELETE FROM forum_post_votes WHERE post_id = :post_id AND user_id = :user_id",
            ['post_id' => $post_id, 'user_id' => $user_id]
        );
    } elseif (is_array($existing)) {
        $this->query(
            "UPDATE forum_post_votes SET vote = :vote WHERE post_id = :post_id AND user_id = :user_id",
            ['vote' => $vote, 'post_id' => $post_id, 'user_id' => $user_id]
        );
    } else {
        $this->query(
            "INSERT INTO forum_post_votes (post_id, user_id, vote) VALUES (:post_id, :user_id, :vote)",
            ['post_id' => $post_id, 'user_id' => $user_id, 'vote' => $vote]
        );
    }

    return $this->getReplyVotes($post_id);
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
