<?php 
require_once('includes/query.php');

$logged_user_id = 1;
?>

<!DOCTYPE html>
<html>
    <head>
        <title>View Articles</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <div includeHTML="navbar.html"></div>
        <script src="includeHTML.js"></script>
        
        <h1>Articles</h1>
        <div class="content">
            <?php $query->do_news_feed($logged_user_id); ?>
        </div>
    </body>
</html>