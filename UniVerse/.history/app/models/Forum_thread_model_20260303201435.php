<?php

class Forum_thread_model extends Model
{

    protected $table = 'forum_threads';
    public function getAllDetails($limit = 50)
    {
        $query = " SELECT ft.*,
                    u.first_name as author_fname,
                    u.last_name as author_lname,
                    fc.name as cat_name,
                    (SELECT COUNT(*) FROM forum_posts WHERE thread_id = ft.thread_id) as reply_count,
                    (SELECT fp.created_at FROM forum_posts fp WHERE fp.thread_id = ft.thread_id ORDER BY fp.created_at DESC LIMIT 1) as last_post_at,
                    (SELECT CONCAT(u2.first_name,' ',u2.last_name) FROM  forum_posts fp2
                    JOIN users u2 ON fp2.user_id = u2.user_id 
                    WHERE fp2.thread_id = ft.thread_id 
                    ORDER BY fp2.created_at DESC LIMIT 1) AS last_post_author
                    FROM {$this->table} ft
                    JOIN users u on ft.user_id = u.user_id
                    JOIN forum_categories fc ON ft.cat_id = fc.cat_id
                    ORDER BY ft.is_pinned DESC,ft.updated_at DESC LIMIT :limit";

        // $query = "SELECT 
        //             ft.*,
        //             u.first_name AS author_fname,
        //             u.last_name AS author_lname,
        //             fc.name AS cat_name,

        //             COUNT(fp.thread_id) AS reply_count,
        //             MAX(fp.created_at) AS last_edited,

        //             CONCAT(u2.first_name, ' ', u2.last_name) AS last_post_author

        //             FROM forum_threads ft
        //             JOIN users u 
        //             ON ft.user_id = u.user_id
        //             JOIN forum_categories fc 
        //             ON ft.cat_id = fc.cat_id

        //              LEFT JOIN forum_posts fp 
        //             ON fp.thread_id = ft.thread_id

        //             LEFT JOIN users u2 
        //             ON fp.user_id = u2.user_id
        //             AND fp.created_at = (
        //                 SELECT MAX(created_at)
        //                 FROM forum_posts
        //                 WHERE thread_id = ft.thread_id)

        //                 GROUP BY ft.thread_id
        //                 ORDER BY ft.is_pinned DESC, ft.updated_at DESC
        //                 LIMIT :limit";


        return $this->fetchAll($query, ['limit' => $limit]);
    }

    public function create_thread($thread){
        $query = "INSERT INTO {$this ->table} (title,cat_id,content,user_id) 
            VALUES (:title,:cat_id,:content,:user_id)";
        
        
        return $this->query($query,$thread);
    }

    public function getIdWithDetails($thread_id){

        $query = "SELECT 
                    ft.*,
                    u.first_name as author_fname,
                    u.last_name as author_lname,
                    u.user_id as author_id,
                    fc.name as cat_name,
                    (SELECT COUNT(*) FROM forum_posts  fp WHERE fp.thread_id = ft.thread_id) as reply_count
                    FROM {$this->table} ft
                    JOIN users u on ft.user_id = u.user_id
                    JOIN forum_categories fc on ft.cat_id = fc.cat_id
                    WHERE ft.thread_id = :thread_id";                    

                    return $this->fetch($query,['thread_id'=>$thread_id]);
    }

    public function countViews($thread_id){
        $query = "UPDATE {$this->table} SET views = views + 1 WHERE thread_id = :thread_id";

        return $this->query($query,['thread_id' => $thread_id]);
    }

    public function getByUser($user_id,$limit=50){
        $query = "SELECT 
                    ft.*,
                    u.first_name as author_fname,
                    u.last_name as author_lname,
                    fc.name as cat_name,
                    (SELECT COUNT(*) FROM forum_posts  fp WHERE fp.thread_id = ft.thread_id) as reply_count,
                    (SELECT fp.created_at FROM forum_posts fp WHERE fp.thread_id = ft.thread_id ORDER BY fp.created_at DESC LIMIT 1) as last_post_at
                  FROM {$this->table} ft
                  JOIN users u ON ft.user_id = u.user_id
                  JOIN forum_categories fc ON ft.cat_id = fc.cat_id
                  WHERE ft.user_id = :user_id
                  ORDER BY ft.created_at DESC
                  LIMIT :limit";

                        return $this->fetchAll($query,['user_id'=>$user_id,'limit'=>$limit]);

    }

    public function update_post($thread_id,$updatedData){

        $query = "UPDATE {$this->table} SET title = :title,content=:content, cat_id =:category WHERE thread_id = :thread_id";

        $updatedData['thread_id'] = $thread_id; 

        return $this->query($query,$updatedData);
    }

    public function delete_post($thread_id){
        $query = "DELETE FROM {$this->table} WHERE thread_id = :thread_id";

        return $this->query($query,['thread_id'=>$thread_id]);
    }

    public function searchByTitle(string $search_term,int $limit = 50)
    {
        $limit = (int)$limit;
        $query = "SELECT ft.*,
        u.first_name as author_fname,
        u.last_name as author_lname,
        fc.name as cat_name,
        (SELECT COUNT(*) FROM forum_posts fp WHERE fp.thread_id = ft.thread_id ) as reply_count,
        (SELECT fp.created_at FROM forum_posts fp WHERE fp.thread_id = ft.thread_id ORDER BY fp.created_at DESC LIMIT 1) as last_posted_at,
        (SELECT CONCAT(u2.first_name,' ',u2.last_name) 
        FROM forum_posts fp2 
        JOIN users u2 ON fp2.user_id = u2.user_id
        WhERE fp2.thread_id = ft.thread_id 
        ORDER BY fp2.created_at DESC LIMIT 1) AS last_posted_author
        FROM {$this->table} ft
        JOIN users u ON ft.user_id = u.user_id
        JOIN forum_categories fc ON ft.cat_id = fc.cat_id
        WHERE ft.title LIKE :search_term
        ORDER BY ft.is_pinned DESC,ft.updated_at DESC LIMIT $limit";

        return $this->fetchAll($query,['search_term' =>'%' . $search_term . '%']) ?: [];
    }

    public function getThreadVotes(int $thread_id){
        $query = "SELECT 
                    SUM(CASE WHEN vote = 1 THEN 1 ELSE 0 END) AS likes,
                    SUM(CASE WHEN vote = -1 THEN 1 ELSE 0 END) AS dislikes
                    FROM forum_thread_votes
                    WHERE thread_id = :thread_id";

        $row = $this->fetch($query,['thread_id' => $thread_id]);

        return [
            'likes' => (int)($row['likes'] ?? 0),
            'dislikes' => (int)($row['dislikes'] ?? 0)
        ];
    }

    public function setVote(int $thread_id,int $user_id,int $vote){
        $vote = ($vote === -1) ? -1 : 1;

        $existing = $this->fetch(
            "SELECT vote FROM forum_thread_votes WHERE thread_id = :thread"
        )
    }
}
