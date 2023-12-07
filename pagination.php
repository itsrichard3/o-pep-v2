<?php
include_once("./app/config/db.php");
if(isset($_POST['page']) && isset($_POST['theme'])) {
    $page = $_POST['page']; // page 1
    $theme = $_POST['theme']; // theme 1

    $pagination = ($page - 1) *10;
    
    $select = $con->prepare("SELECT * FROM article WHERE theme_id = $theme LIMIT $pagination,10");
    $select->execute();
        $result = $select->get_result();
        while($row = $result->fetch_assoc()) {
            ?>
            <div class="card h-auto col-sm px-0 mx-0" data-key="<?php echo $articleID?>">
                        <h1><?php echo $row['article_title']?></h1>
                        <h3><?php echo $row['article_text']?></h3>
                    </div>
            <?php
}
}

?>