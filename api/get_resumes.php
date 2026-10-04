<?php
require "../config/config.php";
header("Content-Type: application/json; charset=UTF-8");

$sql = "SELECT id, name, email, template, photo, updated_at FROM resumes ORDER BY updated_at DESC";
$result = $conn->query($sql);

$resumes = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $resumes[] = $row;
    }
}

echo json_encode([
    "status" => "success",
    "count" => count($resumes),
    "data" => $resumes
], JSON_UNESCAPED_UNICODE);
?>
