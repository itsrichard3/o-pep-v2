<?php
include_once("./app/config/db.php");
if(isset($_GET['themesearch'])){
    $idtheme = $_GET['themesearch'];
    $selec = $con->prepare("SELECT * FROM article WHERE theme_id = $idtheme LIMIT 10");
    $selec->execute();
    $result = $selec->get_result();
    while($row = $result->fetch_assoc()) {
        $articleID = $row['article_id'];
        ?>
        <div onclick="attachClickListeners(<?php echo $articleID?>)" class="card h-auto col-sm px-0 mx-0" data-key="<?php echo $row['article_id']?>">
                        <h1><?php echo $row['article_title']?></h1>
                        <h3><?php echo $row['article_text']?></h3>
                    </div>
        <?php
}
}
?>



                    