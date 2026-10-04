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
    $stmt->bind_param("sssssssssssssssssssi",
        $name,$phone,$email,$address,$linkedin,$github,$objective,$educationJson,$skills,$projectsJson,
        $company,$position,$experienceDate,$responsibilities,$certifications,$achievements,$languages,$template,$photoPath,$id
    );
    $stmt->execute();
} else {
    $sql = "INSERT INTO resumes (name,phone,email,address,linkedin,github,objective,education,skills,projects,company,position,experience_date,responsibilities,certifications,achievements,languages,template,photo) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssssss",
        $name,$phone,$email,$address,$linkedin,$github,$objective,$educationJson,$skills,$projectsJson,
        $company,$position,$experienceDate,$responsibilities,$certifications,$achievements,$languages,$template,$photoPath
    );
    $stmt->execute();
    $id = $stmt->insert_id;
}

header("Location: ../pages/view_resume.php?id=" . $id . "&saved=1");
exit;
?>