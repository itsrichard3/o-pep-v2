<?php
include_once('./app/config/db.php');
session_start();
if(isset($_SESSION['user_id'])) {
   $userid = $_SESSION['user_id']; 
   if(isset($_POST['comment']) && isset($_POST['article'])) {
    $commentvalue = $_POST['comment'];
    $articleID = $_POST['article'];
    $commt = $con->prepare('INSERT INTO comments (article_id,user_id,comment_text) VALUES (?,?,?)');
$commt->bind_param('iis',$articleID,$userid,$commentvalue);
$commt->execute();
$commt->close();

$select = $con->prepare("SELECT * FROM comments WHERE article_id = ?");
$select->bind_param('i',$articleID);
$select->execute();
$result = $select->get_result();
while($row = $result->fetch_assoc()) {
    ?>
    <p><?php echo $row['comment_text']?></p>
    <?php
}
}
}

?>