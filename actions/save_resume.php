<?php
require "../config/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.html");
    exit;
}

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
$name = trim($_POST["name"] ?? "");

if ($name === "") {
    die("Name is required.");
}

function arr($key) {
    return isset($_POST[$key]) && is_array($_POST[$key]) ? $_POST[$key] : [];
}

$degrees = arr("degree");
$colleges = arr("college");
$years = arr("year");
$percentages = arr("percentage");

$education = [];
$max = max(count($degrees), count($colleges), count($years), count($percentages));
for ($i = 0; $i < $max; $i++) {
    $row = [
        "degree" => trim($degrees[$i] ?? ""),
        "college" => trim($colleges[$i] ?? ""),
        "year" => trim($years[$i] ?? ""),
        "percentage" => trim($percentages[$i] ?? "")
    ];
    if (implode("", $row) !== "") $education[] = $row;
}

$titles = arr("project_title");
$techs = arr("project_technology");
$descs = arr("project_description");

$projects = [];
$max = max(count($titles), count($techs), count($descs));
for ($i = 0; $i < $max; $i++) {
    $row = [
        "title" => trim($titles[$i] ?? ""),
        "technology" => trim($techs[$i] ?? ""),
        "description" => trim($descs[$i] ?? "")
    ];
    if (implode("", $row) !== "") $projects[] = $row;
}

$photoPath = "";
$oldPhotoPath = "";
$newPhotoFile = "";
if ($id > 0) {
    $photoStmt = $conn->prepare("SELECT photo FROM resumes WHERE id=?");
    if (!$photoStmt) {
        die("Could not load the existing resume photo.");
    }
    $photoStmt->bind_param("i", $id);
    if (!$photoStmt->execute()) {
        die("Could not load the existing resume photo.");
    }
    $existingResume = $photoStmt->get_result()->fetch_assoc();
    if (!$existingResume) {
        die("Resume not found.");
    }
    $oldPhotoPath = $existingResume["photo"] ?? "";
    $photoPath = $oldPhotoPath;
}

if (!empty($_POST["remove_photo"])) {
    $photoPath = "";
}

if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] !== UPLOAD_ERR_NO_FILE) {
    $upload = $_FILES["photo"];
    if ($upload["error"] !== UPLOAD_ERR_OK) {
        die("The photo upload failed. Please try again.");
    }
    if ($upload["size"] > 5 * 1024 * 1024) {
        die("The photo must be 5 MB or smaller.");
    }
    if (!is_uploaded_file($upload["tmp_name"])) {
        die("The uploaded photo could not be verified.");
    }

    $allowedTypes = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/gif" => "gif",
        "image/webp" => "webp"
    ];
    $fileInfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileInfo->file($upload["tmp_name"]);
    if (!isset($allowedTypes[$mimeType]) || @getimagesize($upload["tmp_name"]) === false) {
        die("Please upload a valid JPG, PNG, GIF, or WebP image.");
    }

    $uploadDirectory = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads";
    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
        die("The photo upload directory could not be created.");
    }

    $fileName = bin2hex(random_bytes(16)) . "." . $allowedTypes[$mimeType];
    $newPhotoFile = $uploadDirectory . DIRECTORY_SEPARATOR . $fileName;
    if (!move_uploaded_file($upload["tmp_name"], $newPhotoFile)) {
        die("The photo could not be saved. Please try again.");
    }
    $oldPhotoPath = $photoPath;
    $photoPath = "uploads/" . $fileName;
}
$phone = trim($_POST["phone"] ?? "");
$email = trim($_POST["email"] ?? "");
$address = trim($_POST["address"] ?? "");
$linkedin = trim($_POST["linkedin"] ?? "");
$github = trim($_POST["github"] ?? "");
$objective = trim($_POST["objective"] ?? "");
$skills = trim($_POST["skills"] ?? "");
$company = trim($_POST["company"] ?? "");
$position = trim($_POST["position"] ?? "");
$experienceDate = trim($_POST["experience_date"] ?? "");
$responsibilities = trim($_POST["responsibilities"] ?? "");
$certifications = trim($_POST["certifications"] ?? "");
$achievements = trim($_POST["achievements"] ?? "");
$languages = trim($_POST["languages"] ?? "");
$template = $_POST["template"] ?? "template1";

$educationJson = json_encode($education, JSON_UNESCAPED_UNICODE);
$projectsJson = json_encode($projects, JSON_UNESCAPED_UNICODE);

if ($id > 0) {
    $sql = "UPDATE resumes SET name=?, phone=?, email=?, address=?, linkedin=?, github=?, objective=?, education=?, skills=?, projects=?, company=?, position=?, experience_date=?, responsibilities=?, certifications=?, achievements=?, languages=?, template=?, photo=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        if ($newPhotoFile !== "" && is_file($newPhotoFile)) unlink($newPhotoFile);
        die("Could not prepare the resume update.");
    }
    $stmt->bind_param("sssssssssssssssssssi",
        $name,$phone,$email,$address,$linkedin,$github,$objective,$educationJson,$skills,$projectsJson,
        $company,$position,$experienceDate,$responsibilities,$certifications,$achievements,$languages,$template,$photoPath,$id
    );
    if (!$stmt->execute()) {
        if ($newPhotoFile !== "" && is_file($newPhotoFile)) unlink($newPhotoFile);
        die("Could not save the resume: " . htmlspecialchars($stmt->error, ENT_QUOTES, "UTF-8"));
    }
} else {
    $sql = "INSERT INTO resumes (name,phone,email,address,linkedin,github,objective,education,skills,projects,company,position,experience_date,responsibilities,certifications,achievements,languages,template,photo) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        if ($newPhotoFile !== "" && is_file($newPhotoFile)) unlink($newPhotoFile);
        die("Could not prepare the resume.");
    }
    $stmt->bind_param("sssssssssssssssssss",
        $name,$phone,$email,$address,$linkedin,$github,$objective,$educationJson,$skills,$projectsJson,
        $company,$position,$experienceDate,$responsibilities,$certifications,$achievements,$languages,$template,$photoPath
    );
    if (!$stmt->execute()) {
        if ($newPhotoFile !== "" && is_file($newPhotoFile)) unlink($newPhotoFile);
        die("Could not save the resume: " . htmlspecialchars($stmt->error, ENT_QUOTES, "UTF-8"));
    }
    $id = $stmt->insert_id;
}

if ($oldPhotoPath !== "" && $oldPhotoPath !== $photoPath
    && strpos($oldPhotoPath, "uploads/") === 0
    && basename($oldPhotoPath) === substr($oldPhotoPath, strlen("uploads/"))) {
    $oldPhotoFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . basename($oldPhotoPath);
    if (is_file($oldPhotoFile)) {
        unlink($oldPhotoFile);
    }
}

header("Location: ../pages/view_resume.php?id=" . $id . "&saved=1");
exit;
?>