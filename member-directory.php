<?php 
require_once('includes/query.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Member Directory</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <div includeHTML="navbar.html"></div>
        <script src="includeHTML.js"></script>
        <h1>Member Directory</h1>
        <div class="content">
            <?php $query->do_user_directory(); ?>
        </div>
    </body>
</html>