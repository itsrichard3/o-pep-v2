<?php
include_once('./app/config/db.php');
session_start();

// if(isset($_SESSION['user_id'])){
//     $user_id = $_SESSION['user_id'];
//     echo ''.$user_id.'';
// }
if(isset($_GET['COMMENTUSER']) && isset($_GET['ARTICLE_ID']) && isset($_GET['COMMENTCVALUE'])){
    $COMMENTUSER = $_GET['COMMENTUSER'];
    $COMMENTVALUE = $_GET['COMMENTVALUE'];
    $ARTICLE_ID = $_GET['ARTICLE_ID'];

    $comment = $con->prepare('INSERT INTO comments (user_id,comment_text,artile_id) VELUES (?,?,?)');
    $comment->bind_param('isi', $COMMENTUSER, $COMMENTVALUE, $ARTICLE_ID);
    $comment->execute();
}

?>

