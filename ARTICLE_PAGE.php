<?php
include_once("./app/config/db.php");
session_start();
if(isset($_SESSION["user_id"])){
$user_id = $_SESSION["user_id"];
echo "".$user_id."<br>";
}
else{
    header('location: index.php');
}
if(isset($_GET["articleid"])) {
    $articleID = $_GET["articleid"];
    echo $articleID;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <?php
    $selection = $con->prepare("SELECT * FROM article,users WHERE  article.article_id = ?");
    $selection->bind_param('i',$articleID);
    $selection->execute();
    $result = $selection->get_result();
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <div clas="d-flex">
        <H1 class="text-center"><?php echo $row['article_title']?></h1>
       <?php
       if($row['article_user'] == $user_id || $row['role_id'] == 2){
        ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Modify</button>
        <?php
       }
       ?>
        </div>


        <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">ARTICLE ID : <?php echo $row['article_id']?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>



        <div>
            <div class="w-25">
                <img src="./assets/imgs/<?php echo $row['article_img']?>" alt="">
            </div>
            <div>
                <p><?php echo $row['article_text']?></p>
            </div>
        </div>


        <h2>COMMENTS</h2>
            <div>
            <input type="text" id="COMMENT" placeholder="Add comment..." name="ADDCOMMENT">
            <button id="ADDCOMMENT">COMMENT</button>
            </div>
       <div class="comments">
         <?php

            $comment = $con->prepare('SELECT * FROM comments WHERE  article_id = ?');
            $comment->bind_param('i',$articleID);
            $comment->execute();
            $resultcomments = $comment->get_result();
            while($row = $resultcomments->fetch_assoc()) {
                $users = $con->prepare("SELECT * FROM users WHERE user_id = ?");
                $users->bind_param('i',$user_id);
                $users->execute();
                $resultuser = $users->get_result();
                $rowuser = $resultuser->fetch_assoc();
                if($row['user_id'] == $user_id|| $rowuser['role_id']==2 ){
                    ?>
                    <div>
                        <p><?php echo $row['comment_text']?></p>
                        <button>MODIFY</button>
                        <button>DELETE</button>
                    </div>
                    <?php
                }
                else {
                    ?>
                    <p><?php echo $row['comment_text']?></p>
                    <?php
                }
            }
        
        ?>
       </div>


        <?php
    }

    
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        var sendcomment =document.querySelector('#ADDCOMMENT');
        var inputcomment = document.querySelector('#COMMENT');
        // var placecomment =document.querySelector('.comments');

        sendcomment.addEventListener('click' ,function () {
            let commentvalue = inputcomment.value;
            
            let XML = new XMLHttpRequest();

            XML.onreadystatechange =function () {
             if(this.status == 200) {
                console.log("SUCCEFULLY ADDED");
             }
            }

            XML.open('GET','comment.php?COMMENTUSER=<?php echo $user_id?>' + '&ARTICLE_ID=<?php echo $articleID?>' + '&COMMENTCVALUE='+commentvalue)
            XML.send();
        } )
    </script>
</body>
</html>