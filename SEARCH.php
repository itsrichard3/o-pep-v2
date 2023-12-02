<?php
include_once("./app/config/db.php");
if(isset($_GET['search'])) {
    $search = $_GET['search'];

    $searchfield = $con->prepare("SELECT * FROM article WHERE article_title LIKE '%$search%'; ");
    $searchfield->execute();
    $result = $searchfield->get_result();
    while($row = $result->fetch_assoc()) {
        ?>
        <div class="card">
                        <h1><?php echo $row['article_title']?></h1>
                        <h3><?php echo $row['article_text']?></h3>
                    </div>
                    <input type="text" placeholder="add comment">
                    <?php
                    $comment = $con->prepare("SELECT * FROM comments where article_id = ?");
                    $comment->bind_param("i",$articleID);
                    $comment->execute();
                    $resultcomment = $comment->get_result();    
                    while($row = $resultcomment->fetch_assoc()) {
                        ?>
                        <p><?php echo $row['comment_text']?></p>
                        <?php

    }
}
}
?>

