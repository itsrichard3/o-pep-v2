<?php
include_once('./app/config/db.php');
if(isset($_GET['array']) && $_GET['array'] !=='') {
    $ids = json_decode($_GET['array']);


    $placeholders = rtrim(str_repeat('?,', count($ids)), ',');


    $filter = $con->prepare("SELECT DISTINCT article.* FROM article 
    JOIN article_tag ON article_tag.article_id=article.article_id
    JOIN tag ON tag.tag_id = article_tag.tag_id
    WHERE article_tag.tag_id IN ($placeholders);
    ");

    $filter->bind_param(str_repeat('i',count($ids)),...$ids);
    $filter->execute();
    $result = $filter->get_result();
    while($row = $result->fetch_assoc()) {
        ?>
        <div onclick="attachClickListeners(<?php echo $row['article_id']?>)" class="card h-auto col-sm px-0 mx-0" data-key="<?php echo $row['article_id']?>">
                        <h1><?php echo $row['article_title']?></h1>
                        <h3><?php echo $row['article_text']?></h3>
                    </div>
        <?php
    }
}
?>