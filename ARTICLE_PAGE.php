<?php
include_once("./app/config/db.php");
session_start();
if(isset($_SESSION["user_id"])){
$user_id = $_SESSION["user_id"];
}
else{
    header('location: index.php');
}
if(isset($_GET["articleid"])) {
    $articleID = $_GET["articleid"];
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
    $selection = $con->prepare("SELECT * FROM article,users WHERE  article.article_id = ? AND  users.user_id = ?");
    $selection->bind_param('ii',$articleID,$user_id);
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?=$row['article_title']?>">Modify</button>
        <?php
       }
       ?>
        </div>


        <!-- Modal -->
<div class="modal fade" id="<?=$row['article_title']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

         <!-- HERE CONTENT -->


         <?php 
        if(!isset($_GET['ARTICLE_ID'])) {
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
                    <p><?php echo $row['comment_text']?></p>
                    <?php
                }
            }
            
        }
         ?>

       </div>


        <?php
    }

    
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        var sendcomment =document.querySelector('#ADDCOMMENT');
        var inputcomment = document.querySelector('#COMMENT');
        var placecomment =document.querySelector('.comments');


        // BOTTON SEND COMMENTS
        sendcomment.addEventListener('click' ,function () {
            let commentvalue = inputcomment.value;
            
            let XML = new XMLHttpRequest();

            XML.onreadystatechange =function () {
             if(this.status == 200) {
                fetchUpdatedComments();
                commentvalue = '';
             }
            }

            XML.open('POST', 'comment.php');
    XML.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    XML.send(
        'COMMENTUSER=<?php echo $user_id?>&ARTICLE_ID=<?php echo $articleID?>&COMMENTVALUE=' + commentvalue
    );
        } )


            // fetching DATAA FROM COMMENTS
        function fetchUpdatedComments() {
        let commentFetch = new XMLHttpRequest();

        commentFetch.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                placecomment.innerHTML = this.responseText; 
            }
        }

        
        commentFetch.open('GET', 'fetchcomments.php?ARTICLE_ID=<?php echo $articleID?>' + '&user=<?php echo $user_id?>');
        commentFetch.send();
    }



    // DELETING COMMENTS
            function DELETE() {
                var DELETE =document.querySelectorAll('.DELETE');
        DELETE.forEach(btn => {
                            btn.addEventListener('click' , function() {
                                let deletevalue = this.value;
                                Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                if (result.isConfirmed) {
                    let XML = new XMLHttpRequest();
                    XML.onreadystatechange=function() {
                        if(this.status==200) {
                            
                            fetchUpdatedComments();
                            Swal.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success"
                    });
                        }
                    }
                    

                    XML.open('GET','delete_comment.php?DELETEID='+deletevalue);
                    XML.send();
                    
                }
                });
            })
        })
        
            }
    



   


    </script>
</body>
</html>