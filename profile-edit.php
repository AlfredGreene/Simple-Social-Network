<?php 
require_once('includes/query.php');
require_once('includes/insert.php');

$logged_user_id = 1;

if (!empty($_POST)) {
    $update = $insert->update_user($logged_user_id, $_POST);
}

$user = $query->load_user_object($logged_user_id);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit Profile</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <div includeHTML="navbar.html"></div>
        <script src="includeHTML.js"></script>
        <h1>Edit Profile</h1>
        <div class="content">
            <form method="post">
                <p>
                    <label class="labels" for="name">Full Name:</label>
                    <input name="user_name" type="text" value="<?php echo $user->user_name; ?>" />
                </p>
                <p>
                    <label class="labels" for="email">Email Address:</label>
                    <input name="user_email" type="text" value="<?php echo $user->user_email; ?>" />
                </p>
                <p>
                    <label class="labels" for="password">Password:</label>
                    <input name="user_password" type="password" value="<?php echo $user->user_password; ?>" />
                </p>
                <p>
                    <input type="submit" value="Submit" />
                </p>
            </form>
        </div>
    </body>
</html>