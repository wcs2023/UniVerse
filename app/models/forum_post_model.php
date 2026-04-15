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

    // public function getReplyVotes(int $post_id)
    // {
    //     $query = "SELECT
    //             SUM(CASE WHEN vote = 1 THEN 1 ELSE 0 END) AS likes,
    //             SUM(CASE WHEN vote = -1 THEN 1 ELSE 0 END) AS dislikes
    //           FROM forum_post_votes
    //           WHERE post_id = :post_id";

    //     $row = $this->fetch($query, ['post_id' => $post_id]);

    //     return [
    //         'likes' => (int)($row['likes'] ?? 0),
    //         'dislikes' => (int)($row['dislikes'] ?? 0)
    //     ];
    // }

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


//     public function setReplyVote(int $post_id, int $user_id, int $vote)
//     {
//         $vote = ($vote === -1) ? -1 : 1;

//         $existing = $this->fetch(
//             "SELECT vote FROM forum_post_votes WHERE post_id = :post_id AND user_id = :user_id",
//             ['post_id' => $post_id, 'user_id' => $user_id]
//         );

//         $existing_vote = 0;

//         if (is_array($existing) && (int)$existing['vote'] === $vote) {
//             $this->query(
//                 "DELETE FROM forum_post_votes WHERE post_id = :post_id AND user_id = :user_id",
//                 ['post_id' => $post_id, 'user_id' => $user_id]
//             );

//             $existing_vote = 0;
//         } elseif (is_array($existing)) {
//             $this->query(
//                 "UPDATE forum_post_votes SET vote = :vote WHERE post_id = :post_id AND user_id = :user_id",
//                 ['vote' => $vote, 'post_id' => $post_id, 'user_id' => $user_id]
//             );

//             $existing_vote = $vote;
//         } else {
//             $this->query(
//                 "INSERT INTO forum_post_votes (post_id, user_id, vote) VALUES (:post_id, :user_id, :vote)",
//                 ['post_id' => $post_id, 'user_id' => $user_id, 'vote' => $vote]
//             );

//             $existing_vote = $vote;
//         }

//         $count = $this->getReplyVotes($post_id);

//         return [
//             'likes' => $count['likes'],
//             'dislikes' => $count['dislikes'],
//             'user_vote' => $existing_vote
//         ];
//     }

    public function setReplyVote(int $post_id, int $user_id, int $vote)
    {
        $vote = ($vote === -1) ? -1 : 1;

        $existing = $this->fetch(
            "SELECT vote FROM forum_post_votes WHERE post_id = :post_id AND user_id = :user_id",
            ['post_id' => $post_id, 'user_id' => $user_id]
        );

        $existing_vote = 0;

        if (is_array($existing) && (int)$existing['vote'] === $vote) {
            $this->query(
                "DELETE FROM forum_post_votes WHERE post_id = :post_id AND user_id = :user_id",
                ['post_id' => $post_id, 'user_id' => $user_id]
            );

            $existing_vote = 0;
        } elseif (is_array($existing)) {
            $this->query(
                "UPDATE forum_post_votes SET vote = :vote WHERE post_id = :post_id AND user_id = :user_id",
                ['vote' => $vote, 'post_id' => $post_id, 'user_id' => $user_id]
            );

            $existing_vote = $vote;
        } else {
            $this->query(
                "INSERT INTO forum_post_votes (post_id, user_id, vote) VALUES (:post_id, :user_id, :vote)",
                ['post_id' => $post_id, 'user_id' => $user_id, 'vote' => $vote]
            );

            $existing_vote = $vote;
        }

        $count = $this->getReplyVotes($post_id, $user_id);

        return [
            'likes' => $count['likes'],
            'dislikes' => $count['dislikes'],
            'user_vote' => $existing_vote
        ];
    }

    public function getReplyVotes(int $post_id, ?int $user_id = null)
    {
        $query = "SELECT
                    COALESCE(SUM(CASE WHEN vote = 1 THEN 1 ELSE 0 END), 0) AS likes,
                    COALESCE(SUM(CASE WHEN vote = -1 THEN 1 ELSE 0 END), 0) AS dislikes";

        if ($user_id !== null) {
            $query .= ",
                    COALESCE((
                        SELECT vote
                        FROM forum_post_votes
                        WHERE post_id = :sub_post_id
                        AND user_id = :sub_user_id
                        LIMIT 1
                    ), 0) AS user_vote";
        } else {
            $query .= ",
                    0 AS user_vote";
        }

        $query .= " FROM forum_post_votes
                    WHERE post_id = :main_post_id";

        $params = [
            'main_post_id' => $post_id
        ];

        if ($user_id !== null) {
            $params['sub_post_id'] = $post_id;
            $params['sub_user_id'] = $user_id;
        }

        $row = $this->fetch($query, $params);

        return [
            'likes' => (int)($row['likes'] ?? 0),
            'dislikes' => (int)($row['dislikes'] ?? 0),
            'user_vote' => (int)($row['user_vote'] ?? 0)
        ];
    }
}


