<?php
require_once("./app/config/db.php");


function addT($data)
{
  global $con;
  $file = $_FILES['theme_img']['name'];
  $folder = './assets/imgs/' . $file;
  $fileTmp = $_FILES['theme_img']['tmp_name'];
  $query = "INSERT INTO theme(theme_name, theme_img) VALUES(?,?)";
  $stmt = $con->prepare($query);
  if ($stmt) {
    $stmt->bind_param("ss", $data["theme_name"], $data["theme_img"]);
    if ($stmt->execute()) {
      move_uploaded_file($fileTmp, $folder);
    }
    if ($stmt->affected_rows > 0) {
      return true;
    } else {
      return false;
    }
    $stmt->close();
  } else {
    return "Error preparing statement: " . $con->error;
  }
}


function getAllT()
{
  global $con;
  $query = "SELECT * FROM theme";
  $stmt = $con->prepare($query);
  $stmt->execute();

  return $stmt->get_result();
}


function updateT($id, $theme_name)
{
  global $con;
  $query = "UPDATE theme SET theme_name = ? WHERE theme_id = ?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("si", $theme_name, $id);
  if ($stmt->execute()) {
    return true;
  } else {
    header("Location: index.php");
  }
}


function deleteT($id)
{
  global $con;
  $query = "DELETE FROM theme WHERE theme_id = ?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  if ($stmt->affected_rows > 0) {
    return true;
  } else {
    return false;
  }
}
