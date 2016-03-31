<?php 
require_once('includes/query.php');
require_once('includes/insert.php');

if (!empty($_POST)) {
    if ($_POST['type'] == 'like') {
        $like_article = $insert->like_article($_POST['article_id'], $_POST['user_id']);
    }
    
    if ($_POST['type'] == 'unlike') {
        $unlike_article = $insert->unlike_article($_POST['article_id'], $_POST['user_id']);
    }
}

$logged_user_id = 1;

if (!empty($_GET['aid'])) {
    $article_id = $_GET['aid'];
    $article = $query->load_article_object($article_id);
    $user = $query->load_user_object($article->author_id);
    
    if ($logged_user_id == $article->author_id) {
        $mine = true;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $article->article_title ?></title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <div includeHTML="navbar.html"></div>
        <script src="includeHTML.js"></script>
        <h1><?php echo $article->article_title ?></h1>
        <div class="content">
            <h3>By: <a href="/social/profile-view.php?uid=<?php echo $user->ID; ?>"><?php echo $user->user_name; ?></a></h3>
            <p><?php echo $article->article_content; ?></p>
        </div>
    </body>
</html>