<?php
include_once("./app/config/db.php");
if(isset($_GET['themesearch'])){
    $idtheme = $_GET['themesearch'];
    $selec = $con->prepare("SELECT * FROM article WHERE theme_id = $idtheme");
    $selec->execute();
    $result = $selec->get_result();
    while($row = $result->fetch_assoc()) {
        $articleID = $row['article_id'];
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



                    