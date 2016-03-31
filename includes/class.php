<?php 
// main class
if (!class_exists('Joomba')) {
    class Joomba {
        function register($redirect) {
            global $db;
            
            // Check to make sure form submission is coming from our script
            $current = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            
            // full url of the page from which the form was submitted
            $referrer = $_SERVER['HTTP_REFERER'];
            
            // Check to see if the $_POST array has data. if so, process the form data.
            if (!empty($_POST)) {
                
                // run the check to see if the form was submitted from our site.
                // If data is not from our site, we don't allow the data to go through.
                if ($referrer == $current) {
                    
                    //require our db class
                    require_once('db.php');
                    
                    // Set up variable we'll need to pass to our insert method: 
                    
                    // table to insert data into
                    $table = 'net_user';
                    
                    // fields in that table we want to insert data into
                    $fields = array('user_name', 'user_email', 'user_password', 'user_registered');
                    
                    // values from our registration form
                    $values = $db->clean($_POST);
                    
                    // break apart our $_POST array (so we can store password securely)
                    $username = $_POST['name'];
                    $useremail = $_POST['email'];
                    $userpassword = $_POST['password'];
                    $userreg = $_POST['date'];
                    
                    // we create a NONCE using action, user email (login), timestamp, and the NONCE SALT
                    $nonce = md5('registration-' . $useremail . $userreg . NONCE_SALT);
                    
                    // we hash password 
                    $userpassword = $db->hash_password($userpassword, $nonce);
                    
                    // Recompile our $value array to insert into the db 
                    $values = array(
                        'name' => $username,
                        'email' => $useremail,
                        'password' => $userpassword,
                        'date' => $userreg
                    );
                    
                    // insert our data 
                    $insert = $db->insert($link, $table, $fields, $values);
                    
                    if ($insert == TRUE) {
                        $url = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                        $aredirect = str_replace('register.php', $redirect, $url);
                        
                        header("Location: $redirect?reg-true");
                        exit;
                    }
                } else {
                    die('Your form submission did not come from the correct page. Please check with the site administrator.');
                }
            }
        }
        
        function login($redirect) {
            global $db;
            
            if (!empty($_POST)) {
                
                // clean form data
                $values = $db->clean($_POST);
                
                // email and password submitted by the user
                $subemail = $values['email'];
                $subpass = $values['password'];
                
                // table to select data from
                $table = 'net_user';
                
                // Run our query to get all data from the user table where the user email matches submitted email
                $sql = "SELECT * FROM $table WHERE user_email = '" . $subemail . "'";
                $results = $db->select($sql);
                
                // Kill the script if no email matches submitted email
                if (!$results) {
                    die('Sorry, that email is not registered!');
                }
                
                // fetch results into an associative array
                $results = mysql_fetch_assoc($results);
                
                // retistration date of the stored matching user
                $storeg = $results['user_registered'];
                
                // hashed password of the stored matching user
                $stopass = $results['user_password'];
                
                // recreate our nonce used at registration
                $nonce = md5('registration-' . $subemail . $storeg . NONCE_SALT);
                
                // rehash the submitted password to see if it matches the stored hash
                $subpass = $db->hash_password($subpass, $nonce);
                
                // check to see if the submitted password matches stored password
                if ($subpass == $stopass) {
                    
                    // if it's a match, we rehash the password to store in a cookie
                    $authnonce = md5('cookie-' . $subemail . $storeg . AUTH_SALT);
                    $authID = $db->hash_password($subpass, $authnonce);
                    
                    // set authorization cookie
                    setcookie('joombologauth[user]', $subemail, 0, '', '', '', true);
                    setcookie('joombologauth[authID]', $authID, 0, '', '', '', true);
                    
                    // build redirect
                    $url = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                    $redirect = str_replace('login.php', $redirect, $url);
                    
                    // redirect to home page
                    header("Location: $redirect");
                    exit;
                } else {
                    return 'invalid';
                }
            } else {
                return 'empty';
            }
        }
        
        function logout() {
            
            // expire our authorizaiton cookie to log the user out
            $idout = setcookie('joombologauth[authID]', '', -3600, '', '', '', true);
            $userout = setcookie('joombologauth[user]', '', -3600, '', '', '', true);
            
            if ($idout == true && $userout == true) {
                return true;
            } else {
                return false;
            }
        }
        
        function checkLogin() {
            global $db;
            
            // grab our auth cookie array
            $cookie = $_COOKIE['joombologauth'];
            
            // set user and authID variables
            $user = $cookie['user'];
            $authID = $cookie['authID'];
            
            // if the cookie values are empty, we redirect to login right away
            // otherwise, run login check.
            if (!empty($cookie)) {
                $table = 'net_user';
                $sql = "SELECT * FROM $table WHERE user_email = '" . $user . "'";
                $results = $db->select($sql);
                
                // kill script if submitted email doesn't exist
                if (!$results) {
                    die('Sorry, that email is not registered!');
                }
                
                // fetch results into an associative array
                $results = mysql_fetch_assoc($results);
                
                // registration date of the stored matching user
                $storeg = $results['user_registered'];
                
                // hashed password of the stored matching user
                $stopass = $results['user_password'];
                
                // rehash password to see if it matches the value stored in the cookie
                $authnonce = md5('cookie-' . $user . $storeg . AUTH_SALT);
                $stopass = $db->hash_password($stopass, $authnonce);
                
                if ($stopass == $authID) {
                    $results = true;
                } else {
                    $results = false;
                }
            } else {
                
                // build redirect
                $url = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                $redirect = str_replace('index.php', 'login.php', $url);
                
                // redirect to home page
                header("Location: $redirect?msg=login");
                exit;
            }
            
            return $results;
        }
    }
}

// instantiate our db class
$j = new Joomba;
?>