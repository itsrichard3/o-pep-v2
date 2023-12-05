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

    <button id="ADDARTICLE">ADD NEW ARTICLE</button>




        <div class="HERO w-100 px-0 mx-0 d-flex">
            <div class="left w-75 bg-danger ">

            <!-- content here -->
            <?php 
            if(!isset($_GET['themesearch']) && !isset($_GET['search']) && !isset($_POST['page'])) {
                $selec = $con->prepare("SELECT * FROM article WHERE theme_id = $idtheme LIMIT 10");
                $selec->execute();
                $result = $selec->get_result();
                while($row = $result->fetch_assoc()) {
                    $articleID = $row['article_id'];
                    ?>
                    <div class="card h-auto col-sm px-0 mx-0" data-key="<?php echo $articleID?>">
                        <h1><?php echo $row['article_title']?></h1>
                        <h3><?php echo $row['article_text']?></h3>
                    </div>
                <?php    
        }
    }
            
            ?>
           


            </div>
        <div class="filter w-25 bg-success d-flex flex-column justify-content-center  align-items-center" style="height:50vh">

            <div class="filter">
                <?php
                $tag = $con->prepare("SELECT * FROM tag
                JOIN theme_tag ON theme_tag.tag_id = tag.tag_id
                JOIN theme ON theme.theme_id = theme_tag.theme_id
                WHERE theme.theme_id = $idtheme;
                ");
                $tag->execute();
                $result = $tag->get_result();
                while($row = $result->fetch_assoc()) {
                    ?>
                   <div>
                   
                    <input type="checkbox" class="BOX"  name="BOX" value="<?php echo $row ['tag_id']?>">
                    <label for="BOX"><?php echo $row['tag_name']?></label>
                   </div>
                    
                    <?php
                }
                ?>
            </div>

        </div>
        </div>

        <div class="pagination  d-flex justify-content-center">
        <?php
            

            $page = $con->prepare("SELECT COUNT(article_id) as totalarticle FROM article WHERE theme_id = '$idtheme'");
            $page->execute();
            $result =$page->get_result();
            $row = $result->fetch_assoc();
            $pagination = $row['totalarticle'];

            $totalpage = ceil($pagination/10);
            if($totalpage>1){
                ?>
                <div class="pagination d-flex justify-content-center">
                    <?php
                    for($i=0+1;$i<=$totalpage;$i++){
                        ?>
                        <button class="page" value="<?php echo $i?>"><?php echo $i?></button>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
        ?>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
           
           
           <script>




                var input =document.querySelector('.search');
                var article =document.querySelectorAll('.card');
                var pagebutton =document.querySelectorAll('.page');


                article.forEach(element => {
                    element.addEventListener('click' , function () {
                            let cardvalue = this.getAttribute('data-key');
                            window.open('ARTICLE_PAGE.php?articleid='+cardvalue)
                    })
                })


                


                function affichage() {
                    let XML = new XMLHttpRequest();

                            XML.onload =function () {
                                if(this.readyState==4 && this.status==200) {
                                    document.querySelector('.left').innerHTML = this.responseText;
                                }
                            }
                            XML.open('GET','AFFICHAGE.php?themesearch=<?php echo $idtheme?>');
                            XML.send();
                }




                input.addEventListener("input" ,inpt => {
                    let value = inpt.target.value;
                    console.log(value);
                    if(value === '') {
                              affichage();  
                    }
                    else {
                                let XML =new XMLHttpRequest();

                            XML.onload = function () {
                                if(this.readyState==4 && this.status==200){
                                    document.querySelector('.left').innerHTML = this.responseText;
                                }
                            }
                            XML.open('GET','SEARCH.php?search='+value + '&theme='+<?php echo $idtheme?>);
                            XML.send();
                    }
                    
                })



                var checkbox =document.querySelectorAll('.BOX');
                var checked = [];


                checkbox.forEach(check => {
                    check.addEventListener('change' , function () {
                        if(this.checked){
                        checked.push(this.value);
                        }
                        else{
                            let index = checked.indexOf(this.value);
                            if(!index!==-1){
                                checked.splice(index,1);
                            }
                        }

                        let XM =new XMLHttpRequest();

                        XM.onload= function () {
                            if(this.status==200 && this.readyState==4) {
                               if(checked.length>0){
                                document.querySelector('.left').innerHTML = this.responseText;
                               }
                               else {
                                    affichage();
                               }
                            }
                        }

                        XM.open('GET','FILTER.php?array='+ JSON.stringify(checked));
                        XM.send();

                    })
                })


                pagebutton.forEach(BTNNM => {
                    BTNNM.addEventListener("click",function () {
                        let pagevalue = this.value;


                        let HTTP = new XMLHttpRequest();

                        HTTP.onreadystatechange =function () {
                            if(this.status==200){
                                document.querySelector('.left').innerHTML = this.responseText;
                            }
                        }

                        HTTP.open('POST','pagination.php');
                        HTTP.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                        HTTP.send("page="+pagevalue + '&theme=' + <?php echo $idtheme?>);
                    })
                })

<<<<<<< HEAD
=======



>>>>>>> 52c2416a0d9a6c0bfbb003e869bb229bdd0473bf
                console.log(checked);

                var ADD =document.querySelector('#ADDARTICLE').addEventListener('click', function() {
                    window.open('ADD_ARTICLE.php?theme=<?php echo $idtheme?>');
                })

            </script>
    </body>
    </html>



