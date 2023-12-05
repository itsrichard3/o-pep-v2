<?php
include_once('./app/config/db.php');

if(isset($_GET['ARTICLE_ID'])) {
    $articleID = $_GET['ARTICLE_ID'];
    $user_id = $_GET['user'];
    $comment = $con->prepare('SELECT * FROM comments WHERE  article_id = ? AND comment_status = "COMMENTED"');
    $comment->bind_param('i',$articleID);
    $comment->execute();
    $resultcomments = $comment->get_result();
    while ($row = $resultcomments->fetch_assoc()) {
        $commentID = $row['comment_id'];
    
        $selected = $con->prepare("SELECT *
            FROM comments
            JOIN users ON comments.user_id = users.user_id
            WHERE comments.comment_id = ?");
        $selected->bind_param('i', $commentID);
        $selected->execute();
        $resultselected = $selected->get_result();
        $rowselected = $resultselected->fetch_assoc();
    
        if ($rowselected) {
            ?>
            <div>
                <h1><?php echo $rowselected['user_name']?></h1>
                <p><?php echo $rowselected['comment_text']?></p>
                <?php
                if ($row['user_id'] == $user_id || $rowselected['role_id'] == 2) {
                    ?>
                    <button>MODIFY</button>
                    <button onclick="DELETE()" value="<?php echo $row['comment_id']?>" name="DELETE" class="DELETE">DELETE</button>
                    <?php
                }
                ?>
            </div>
            <?php
        } else {
            ?>
            <h1><?php echo $rowselected['user_name']?></h1>
            <p><?php echo $row['comment_text']?></p>
            <?php
        }
    }
    
}
            
        
        ?>