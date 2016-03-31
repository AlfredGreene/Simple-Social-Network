<?php 
require_once('db.php');

if (!class_exists('INSERT')) {
    class INSERT {
        public function update_user($user_id, $postdata) {
            global $db;
            
            $table = 'net_user';
            
            $query = "
                        UPDATE $table
                        SET user_email='$postdata[user_email]', user_password='$postdata[user_password]', user_name='$postdata[user_name]'
                        WhERE ID=$user_id
                    ";
            return $db->update($query);
        }
        
        public function add_friend($user_id, $friend_id) {
            global $db;
            
            $table = 'net_friend';
            
            $query = "  INSERT INTO $table (user_id, friend_id)
                        VALUES ('$user_id', '$friend_id')
                     ";
            return $db->insert($query);
        }
        
        public function remove_friend($user_id, $friend_id) {
            global $db;
            
            $table = 'net_friend';
            
            $query = "  DELETE FROM $table
                        WHERE user_id = '$user_id'
                        AND friend_id = '$friend_id'
                     ";
            return $db->insert($query);
        }
        
        public function add_article($user_id, $_POST) {
            global $db;
            
            $table = 'net_article';
            
            $query = "
                        INSERT INTO $table (user_id, post_time, article_title, article_content)
                        VALUES ($user_id, '$_POST[post_time]', '$_POST[article_title]', '$_POST[article_content]')
                    ";
                    
            return $db->insert($query);
        }
    }
}
?>