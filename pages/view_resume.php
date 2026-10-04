<?php
require "../config/config.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$stmt = $conn->prepare("SELECT * FROM resumes WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) die("Resume not found.");

$education = json_decode($data["education"], true);
$projects = json_decode($data["projects"], true);
if (!is_array($education)) $education = [];
if (!is_array($projects)) $projects = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($data["name"]); ?> - CV</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="view-page">
<div class="print-toolbar">
  <a class="btn" href="../index.html">← Dashboard</a>
  <a class="btn" href="resume_form.php?id=<?php echo $id; ?>">Edit</a>
  <button class="btn primary" onclick="window.print()">Print / Save PDF</button>
</div>

<main class="print-wrapper">
<div class="resume <?php echo htmlspecialchars($data["template"]); ?>">

  <div class="cv-header">
    <div class="cv-photo-wrap">
      <div class="fallback-photo"><?php echo strtoupper(substr($data["name"] ?: "U", 0, 1)); ?></div>
    </div>

    <div class="cv-head-text">
      <h1><?php echo htmlspecialchars($data["name"]); ?></h1>
      <p><?php echo htmlspecialchars(trim($data["phone"] . " | " . $data["email"], " |")); ?></p>
      <p><?php echo nl2br(htmlspecialchars($data["address"])); ?></p>
      <p><?php echo htmlspecialchars(trim($data["linkedin"] . " | " . $data["github"], " |")); ?></p>
    </div>
  </div>

  <?php if ($data["objective"]): ?>
  <section class="cv-section"><h2>Profile</h2><p><?php echo nl2br(htmlspecialchars($data["objective"])); ?></p></section>
  <?php endif; ?>

  <?php if ($education): ?>
  <section class="cv-section"><h2>Education</h2>
    <?php foreach ($education as $e): ?>
      <div class="cv-entry">
        <h3><?php echo htmlspecialchars($e["degree"]); ?></h3>
        <p><?php echo htmlspecialchars($e["college"]); ?></p>
        <p><?php echo htmlspecialchars($e["year"]); ?><?php echo $e["percentage"] ? " | ".htmlspecialchars($e["percentage"]) : ""; ?></p>
      </div>
    <?php endforeach; ?>
  </section>
  <?php endif; ?>

  <?php if ($data["skills"]): ?>
  <section class="cv-section"><h2>Skills</h2>
    <div class="skill-list">
      <?php foreach (preg_split('/[,;\n]+/', $data["skills"]) as $skill): if (trim($skill)): ?>
        <span><?php echo htmlspecialchars(trim($skill)); ?></span>
      <?php endif; endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <?php if ($projects): ?>
  <section class="cv-section"><h2>Projects</h2>
    <?php foreach ($projects as $p): ?>
      <div class="cv-entry">
        <h3><?php echo htmlspecialchars($p["title"]); ?></h3>
        <?php if ($p["technology"]): ?><p><strong>Technologies:</strong> <?php echo htmlspecialchars($p["technology"]); ?></p><?php endif; ?>
        <p><?php echo nl2br(htmlspecialchars($p["description"])); ?></p>
      </div>
    <?php endforeach; ?>
  </section>
  <?php endif; ?>

  <?php if ($data["company"] || $data["position"] || $data["responsibilities"]): ?>
  <section class="cv-section"><h2>Work Experience</h2>
    <div class="cv-entry">
      <h3><?php echo htmlspecialchars($data["position"]); ?></h3>
      <p><strong><?php echo htmlspecialchars($data["company"]); ?></strong><?php echo $data["experience_date"] ? " | ".htmlspecialchars($data["experience_date"]) : ""; ?></p>
      <p><?php echo nl2br(htmlspecialchars($data["responsibilities"])); ?></p>
    </div>
  </section>
  <?php endif; ?>

  <?php if ($data["certifications"]): ?><section class="cv-section"><h2>Certifications</h2><p><?php echo nl2br(htmlspecialchars($data["certifications"])); ?></p></section><?php endif; ?>
  <?php if ($data["achievements"]): ?><section class="cv-section"><h2>Achievements</h2><p><?php echo nl2br(htmlspecialchars($data["achievements"])); ?></p></section><?php endif; ?>
  <?php if ($data["languages"]): ?><section class="cv-section"><h2>Languages</h2><p><?php echo htmlspecialchars($data["languages"]); ?></p></section><?php endif; ?>

</div>
</main>
</body>
</html>