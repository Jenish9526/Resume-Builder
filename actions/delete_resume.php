<?php
require "../config/config.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM resumes WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: ../index.html");
exit;
?>