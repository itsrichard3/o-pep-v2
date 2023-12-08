<?php
include_once("./app/config/db.php");
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $idtheme = $_GET['theme'];

    $searchfield = $con->prepare("SELECT * FROM article WHERE article_title LIKE '%$search%' AND theme_id = $idtheme ");
    $searchfield->execute();
    $result = $searchfield->get_result();
    while($row = $result->fetch_assoc()) {
        ?>
        <div onclick="attachClickListeners(<?php echo $row['article_id']?>)" class="card" data-key="<?php echo $row['article_id'] ?>">
                        <h1><?php echo $row['article_title']?></h1>
                        <h3><?php echo $row['article_text']?></h3>
                    </div>
                   <?php
}
}
?>

