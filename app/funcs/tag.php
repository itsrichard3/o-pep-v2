<?php
require_once("./app/config/db.php");


function addTag($data)
{
  global $con;
  $query1 = "INSERT INTO tag(tag_name) VALUES(?)";
  $stmt = $con->prepare($query1);
  $stmt->bind_param("s", $data["tag_name"]);
  if ($stmt->execute()) {
    $tag_id = $con->insert_id;
    $query2 = "INSERT INTO theme_tag(theme_id,tag_id) VALUES(?,?)";
    $stmt = $con->prepare($query2);
    $stmt->bind_param("ii", $data["theme_id"], $tag_id);
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
}


function getAllTag()
{
  global $con;
  $query = "SELECT * FROM tag t JOIN theme_tag tt on t.tag_id = tt.tag_id JOIN theme th ON tt.theme_id = th.theme_id;";
  $stmt = $con->prepare($query);
  $stmt->execute();

  return $stmt->get_result();
}





function deleteTag($id)
{
  global $con;
  $query = "DELETE FROM tag WHERE tag_id = ?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  if ($stmt->affected_rows > 0) {
    return true;
  } else {
    return false;
  }
}
