<?php 
require_once('includes/insert.php');

$logged_user_id = 1;

if (!empty($_POST)) {
    $add_article = $insert->add_article($logged_user_id, $_POST);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Post Article</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <div includeHTML="navbar.html"></div>
        <script src="includeHTML.js"></script>
        <h1>Post Status</h1>
        <div class="content">
            <form method="post">
                <input name="post_time" type="hidden" value="<?php echo time() ?>" />
                <p>Article Title</p>
                <textarea name="article_title"></textarea>
                <p>Article Body</p>
                <textarea name="article_content"></textarea>
                <p>
                    <input type="submit" value="Publish Article" />
                </p>
            </form>
        </div>
    </body>
</html>