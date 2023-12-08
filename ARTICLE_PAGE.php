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
$uniqueID = bin2hex(random_bytes(8));
$uniqueID = preg_replace("/[^A-Za-z]/", '', $uniqueID);
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $uniqueID?>">Modify</button>
        <form action="./delete_article.php" method="post" class="d-inline">
            <input type="hidden" name="articleID" value="<?php echo $articleID ?>">
            <button type="submit" class="btn btn-danger" value="<?php echo $row['article_id']?>">DELETE ARTICLE</button>
        </form>
        <?php
       }
       ?>
        </div>


<!-- Modal -->
<div class="modal fade" id="<?php echo $uniqueID?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">ARTICLE ID : <?php echo $row['article_id']?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="article_title">article_title</label>
        <input type="text" class="w-100 article_title" value="<?php echo $row['article_title']?>">
        <label for="textarea" class="mt-1">article_text</label>
        <textarea class="form-control mt-2 message-text"><?php echo $row['article_text']?></textarea>
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" name="article_image" class="form-control article_image">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="SAVEARTICLE btn btn-primary" value="<?php echo $row['article_id']?>">Save changes</button>
      </div>
    </div>
  </div>
</div>


<?php
$imageData = base64_encode($row['article_img']);
$imageSrc = 'data:image/jpeg;base64,' . $imageData;
?>

        <div>
            <div class="w-25">
                <img src="<?php echo $imageSrc; ?>" alt="">
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
            $n = 0;
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
                        <p class="hehe"></p>
                        <?php
                        if ($row['user_id'] == $user_id || $rowselected['role_id'] == 2) {
                            ?>
                            <button  class="MODIFY btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $row['comment_text']?>" >MODIFY</button>
                            <button onclick="DELETE(<?php echo $row['comment_id']?>)" value="<?php echo $row['comment_id']?>" name="DELETE" class="DELETE">DELETE</button>
                            
                            <!-- Modal -->
<div class="modalmodifycomment modal fade" id="<?php echo $row['comment_text']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">COMMENT ID : <?php echo $row['comment_id']?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" placecomment="new comment ..." class="modifycomment" value="<?php echo $row['comment_text']?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button onclick="modify(this,<?php echo $n?>)" type="button" class="SAVE btn btn-primary" value="<?php echo $row['comment_id']?>" data-bs-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>
                            
                            
                            <?php
                            $n++;
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





    // DELETING COMMENTS
            function DELETE(id) {
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
                    

                    XML.open('GET','delete_comment.php?DELETEID='+id);
                    XML.send();
                    
                }
                });
        
            }




            // modify comment 
            var COMMENTMODIFY =document.querySelectorAll('.modifycomment');
            var SAVE =document.querySelectorAll('.SAVE');


                    function modify (btn,i) {
                        let valuesave =COMMENTMODIFY[i].value;
                    let btnvalue = btn.value;
                    console.log(valuesave);
                    console.log(btnvalue);
                    let XML = new XMLHttpRequest();
                    XML.onreadystatechange = function () {
                        if(this.status==200) {
                            fetchUpdatedComments();
                            location.reload();
                        }
                    }
                    XML.open('GET','MODIFY.php?CMTID='+btnvalue + '&CMTVALUE='+valuesave);
                    XML.send();
                    }
            


                        var save = document.querySelectorAll('.SAVEARTICLE');
            var articletitle = document.querySelectorAll('.article_title');
            var messagetext = document.querySelectorAll('.message-text');
            var articleimage = document.querySelectorAll('.article_image');

            save.forEach((btn, index) => {
                btn.addEventListener('click', function () {
                    let message = messagetext[index].value;
                    let articletitletest = articletitle[index].value;
                    let articleImageFile = articleimage[index].files[0];

                    let savebtn = btn.value;

                    let formData = new FormData(); 
                    formData.append('articleid', savebtn);
                    formData.append('articletitle', articletitletest);
                    formData.append('articlemessage', message);
                    formData.append('articleimg', articleImageFile); 

                    let XML = new XMLHttpRequest();

                    XML.onreadystatechange = function () {
                        if (this.status == 200) {
                            console.log("Successfully updated!");
                            location.reload();
                        }
                    }

                    XML.open('POST', 'modifyarticle.php');
                    XML.send(formData); 
                })
            })


    </script>
</body>
</html>