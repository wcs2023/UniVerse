<?php

class Forum_category_model extends Model{
    protected $table = 'forum_categories';

    public function getAllStats(){

        $query ="SELECT fc.*,
                    COUNT(DISTINCT ft.thread_id) as thread_count,
                    (SELECT COUNT(*) FROM forum_posts fp JOIN forum_threads ft2 ON fp.thread_id = ft2.thread_id WHERE ft2.cat_id = fc.cat_id) as post_count,
                    MAX(ft.updated_at) as last_activity

                    FROM {$this->table} fc 
                    LEFT JOIN forum_threads ft ON fc.cat_id = ft.cat_id
                    GROUP BY fc.cat_id
                    ORDER BY fc.display_order ASC , fc.name ASC";

                    return $this->fetchAll($query);

    }

    public function getOrderedCat(){
        $query = "SELECT * FROM {$this->table} ORDER BY display_order ASC,name ASC";

        return $this->fetchAll($query);
    }

}