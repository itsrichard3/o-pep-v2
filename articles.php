    <?php
    include_once('./app/config/db.php');
    if(isset($_GET['theme'])) {
        $idtheme = $_GET['theme'];
        echo $idtheme;
}
    ?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bootstrap demo</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    </head>
    <body>

    <input class="search" type="text" placeholder="Search for article">
        <div class="HERO w-100 h-100 d-flex">
            <div class="left w-75 bg-danger">

            <!-- content here -->
            <?php 
            if(!isset($_GET['themesearch']) && !isset($_GET['search'])) {
                $selec = $con->prepare("SELECT * FROM article WHERE theme_id = $idtheme");
                $selec->execute();
                $result = $selec->get_result();
                while($row = $result->fetch_assoc()) {
                    $articleID = $row['article_id'];
                    ?>
                    <div class="card" data-key="<?php echo $articleID?>">
                        <h1><?php echo $row['article_title']?></h1>
                        <h3><?php echo $row['article_text']?></h3>
                    </div>
                    <div id="comments-<?php echo $articleID?>">
                    <?php
                    $comment = $con->prepare("SELECT * FROM comments where article_id = ?");
                    $comment->bind_param("i",$articleID);
                    $comment->execute();
                    $resultcomment = $comment->get_result();    
                    while($row = $resultcomment->fetch_assoc()) {
                        ?>
                        <p class="COMMENTTEXT"><?php echo $row['comment_text']?></p>
                        <?php
            }
                ?>
                
                </div>
                    <input type="text" class="comment" placeholder="add comment">
                    <button type="submit" class="submit">Comment</button>
                <?php    
        }
    }
            
            ?>


            </div>
        <div class="filter w-25 bg-success">

            <div class="form">
                <input type="checkbox" value="TEST" name="TEST">
                <label for="">TEST</label>
                <input type="checkbox" value="football" name="football">
                <label for="">football</label>
            </div>

        </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
            <script>
                var input =document.querySelector('.search');
                var commentInputs = document.querySelectorAll('.comment');
                var sendButtons = document.querySelectorAll('.submit');
                var articleID =document.querySelectorAll('.card');

                sendButtons.forEach((btn, index) => {
                    btn.addEventListener('click', function() {
                        let commentValue = commentInputs[index].value;
                        let ARTID =articleID[index].getAttribute('data-key');
                        if(commentValue===''){
                            alert("watchout");
                        }
                        else{
                            let xml = new XMLHttpRequest();

                            xml.onload=function () {
                                if(this.status==200 && this.readyState==4){
                                        document.querySelector(`#comments-${ARTID}`).innerHTML = this.responseText;
                                }
                                else {
                                        console.error('Error',xhr.status);
                                }
                            }

                            xml.open('POST','comment.php',true);
                            xml.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                            xml.send('comment=' + encodeURIComponent(commentValue) + '&article=' + encodeURIComponent(ARTID));
                        }
                        
                    });
                });





                input.addEventListener("input" ,inpt => {
                    let value = inpt.target.value;
                    console.log(value);
                    if(value === '') {
                                let XML = new XMLHttpRequest();

                            XML.onload =function () {
                                if(this.readyState==4 && this.status==200) {
                                    document.querySelector('.left').innerHTML = this.responseText;
                                }
                            }
                            XML.open('GET','AFFICHAGE.php?themesearch=<?php echo $idtheme?>');
                            XML.send();
                    }
                    else {
                                let XML =new XMLHttpRequest();

                            XML.onload = function () {
                                if(this.readyState==4 && this.status==200){
                                    document.querySelector('.left').innerHTML = this.responseText;
                                }
                            }
                            XML.open('GET','SEARCH.php?search='+value);
                            XML.send();
                    }
                    
                })




            </script>
    </body>
    </html>



