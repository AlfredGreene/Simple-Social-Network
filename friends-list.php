<?php 
require_once('includes/query.php');
require_once('includes/insert.php');

$logged_user_id = 1;
$friends = $query->get_friends($logged_user_id);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Friends</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <div includeHTML="navbar.html"></div>
        <script src="includeHTML.js"></script>
        <h1>Friends</h1>
        <div class="content">
            <?php $query->do_friends_list($friends); ?>
        </div>
    </body>
</html>