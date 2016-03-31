<?php 

require_once('db.php');

if (!class_exists('QUERY')) {
    class QUERY {
        public function load_user_object($user_id) {
            global $db;
            
            $table = 'net_user';
            
            $query = "  SELECT * FROM $table
                        WHERE ID = $user_id
                     ";
            $obj = $db->select($query);
            
            if (!$obj) {
                return "No user found";
            }
            
            return $obj[0];
        }
        
        public function load_article_object($article_id) {
            global $db;
            
            $table = 'net_article';
            
            $query = "
                        SELECT * FROM $table
                        WHERE ID = $article_id
                    ";
            $obj = $db->select($query);
            
            if (!$obj) {
                return "No article found";
            }
            
            return $obj[0];
        }
        
        public function load_all_user_objects() {
            global $db;
            
            $table = 'net_user';
            
            $query = "SELECT * FROM $table";
            
            $obj = $db->select($query);
            
            if (!$obj) {
                return "No user found";
            }
            
            return $obj;
        }
        
        public function get_friends($user_id) {
            global $db;
            
            $table = 'net_friend';
            
            $query = "  SELECT ID, friend_id FROM $table
                        WHERE user_id = '$user_id'
                     ";
            $friends = $db->select($query);
            
            foreach ($friends as $friend) {
                $friend_ids[] = $friend->friend_id;
            }
            
            return $friend_ids;
        }
        
        public function get_article_objects($user_id) {
            global $db;
            
            $table = 'net_article';
            
            $friend_ids = $this->get_friends($user_id);
            
            if (!empty($friend_ids)) {
                array_push($friend_ids, $user_id);
            } else {
                $friend_ids = array($user_id);
            }
            
            $accepted_ids = implode(', ', $friend_ids);
            
            $query = "
                        SELECT * FROM $table
                        WHERE user_id IN ($accepted_ids)
                        ORDER BY post_time DESC
                    ";
            $article_objects = $db->select($query);
            
            return $article_objects;
        }
        
        public function get_users_article_objects($user_id) {
            global $db;
            
            $table = 'net_article';
            
            $query = "
                        SELECT * FROM $table
                        WHERE user_id = '$user_id'
                        ORDER BY post_time DESC
                    ";
            
            $user_articles = $db->select($query);
            
            if (!empty($user_articles)) {
                return "No articles found";
            }
            
            return $user_articles;
        }
        
        public function do_user_directory() {
            $users = $this->load_all_user_objects();
            
            foreach ($users as $user) { ?>
                <div class="dir_item">
                    <h3><a href="/social/profile-view.php?uid=<?php echo $user->ID; ?>"><?php echo $user->user_name; ?></a></h3>
                    <p><?php echo $user->user_email; ?></p>
                </div>
            <?php 
            }
        }
        
        public function do_friends_list($friends_array) {
            foreach ($friends_array as $friend_id) {
                $users[] = $this->load_user_object($friend_id);
            }
            
            foreach ($users as $user) { ?>
                <div class="dir_item">
                    <h3><a href="/social/profile-view.php?uid=<?php echo $user->ID; ?>"><?php echo $user->user_name; ?></a></h3>
                </div>
            <?php 
            }
        }
        
        public function do_news_feed($user_id) {
            $article_objects = $this->get_article_objects($user_id);
            
            foreach ($article_objects as $article) { ?>
                <div class="article_item">
                    <?php $user = $this->load_user_object($article->user_id); ?>
                    <h3><a href="/social/profile-view.php?uid=<?php echo $user->ID; ?>"><?php echo $user->user_name; ?></a></h3>
                    <p><?php echo $article->article_content; ?></p>
                </div>
            <?php 
            }
        }
        
        public function do_article_list($user_id) {
            $articles = $this->get_users_article_objects($user_id);
            
            foreach ($articles as $article) { ?>
                <div class="article_item">
                    <?php $user = $this->load_user_object($article->user_id); ?>
                    <h3><a href="/social/article-view.php?aid=<?php echo $article->ID; ?>"><?php echo $article->article_title; ?></a>
                    <p>By: <a href="/social/profile-view.php?uid=<?php echo $user->ID; ?>"><?php echo $user->user_name; ?></a></p>
                </div>
            <?php 
            }
        }
    }
}

?>