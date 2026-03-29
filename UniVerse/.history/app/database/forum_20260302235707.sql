create table forum_categories(
    cat_id int auto_increment primary key,
    name varchar(255) not null,
    description text,
    icon varchar(50) default 'fa-comment',
    display_order int default 0,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp

);

create table forum_threads(
    thread_id int auto_increment primary key,
    cat_id int not null,
    user_id int not null,
    title varchar(255) not null,
    content text not null,
    is_pinned boolean default false,
    is_locked boolean default false,
    views int default 0,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp,
    foreign key (cat_id) references forum_categories(cat_id) on delete cascade,
    foreign key (user_id) references users(user_id) on delete cascade,
    index cat_id_idx(cat_id),
    index user_id_idx(user_id),
    index created_at_idx(created_at)
);

create table forum_posts(
    post_id int auto_increment primary key,
    thread_id int not null,
    user_id int not null,
    content text not null,
    is_edited boolean default false,
    edited_at timestamp null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp,
    foreign key (thread_id) references forum_threads(thread_id) on delete cascade,
    foreign key (user_id) references users(user_id)  on delete cascade,

    index thread_id_idx(thread_id),
    index user_id_idx(user_id),
    index created_at_idx(created_at)

);

INSERT INTO forum_categories (name, description, icon, display_order) VALUES
('University Selection', 'Discuss different universities, programs, and admission requirements', 'fa-graduation-cap', 1),
('Career Planning', 'Share career advice, job market insights, and professional development tips', 'fa-briefcase', 2),
('Study Tips & Advice', 'Exchange study methods, exam preparation strategies, and academic support', 'fa-book-open', 3),
('Scholarships & Financial Aid', 'Information about scholarships, grants, and funding opportunities', 'fa-dollar-sign', 4),
('General Discussion', 'Open discussion about student life, experiences, and other topics', 'fa-comments', 5);

CREATE TABLE forum_post_votes (
  vote_id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT NOT NULL,
  user_id INT NOT NULL,
  vote TINYINT NOT NULL, -- 1 = like, -1 = dislike
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT chk_post_vote CHECK (vote IN (1, -1)),
  UNIQUE KEY uniq_post_user (post_id, user_id),
  INDEX idx_post (post_id),
  INDEX idx_user (user_id),

  CONSTRAINT fk_post_votes_posts 
  FOREIGN KEY (post_id) REFERENCES forum_posts(post_id) ON DELETE CASCADE
);