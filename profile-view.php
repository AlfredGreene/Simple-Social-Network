<?php 
require_once('includes/query.php');
require_once('includes/insert.php');

if (!empty($_POST)) {
    if ($_POST['type'] == 'add') {
        $add_friend = $insert->add_friend($_POST['user_id'], $_POST['firend_id']);
    }
    
    if ($_POST['type'] == 'remove') {
        $remove_friend = $insert->remove_friend($_POST['user_id'], $_POST['friend_id']);
    }
}

$logged_user_id = 1;

if (!empty($_GET['uid'])) {
    $user_id = $_GET['uid'];
    $user - $query->load_user_object($user_id);
    
    if ($logged_user_id == $user_id) {
        $mine = true;
    }
} else {
    $user = $query->load_user_object($logged_user_id);
    $mine = true;
}

$friends = $query->get_friends($logged_user_id);
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $user->user_name; ?></title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <div includeHTML="navbar.html"></div>
        <script src="includeHTML.js"></script>
        <h1>View Profile</h1>
        <div class="content">
            <p>Name: <?php echo $user->user_name; ?></p>
            <?php if (!$mine) : ?>
                <?php if (!in_array($user_id, $friends)) : ?>
                    <p>
                        <form method="post">
                            <input name="user_id" type="hidden" value="<?php echo $logged_user_id; ?>" />
                            <input name="friend_id" type="hidden" value="<?php echo $user_id; ?>" />
                            <input name="type" type="hidden" value="add" />
                            <input type="submit" value="Add Friend" />
                        </form>
                    </p>
                <?php else : ?>
                    <p>
                        <form method="post">
                            <input name="user_id" type="hidden" value="<?php echo $logged_user_id; ?>" />
                            <input name="friend_id" type="hidden" value="<?php echo $user_id; ?>" />
                            <input name="type" type="hidden" value="remove" />
                            <input type="submit" value="Unfriend" />
                        </form>
                    </p>
                <?php endif; ?>
                <h3>Articles by <?php echo $user->user_name; ?></h3>
            <?php else : ?>
                <h3>Your Articles</h3>
            <?php endif; ?>
            <?php $query->do_article_list($user->ID); ?>
        </div>
    </body>
</html>