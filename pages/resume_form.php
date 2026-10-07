<?php
require "../config/config.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$isEdit = $id > 0;
$data = [
    "id"=>0, "name"=>"", "phone"=>"", "email"=>"", "address"=>"",
    "linkedin"=>"", "github"=>"", "objective"=>"", "education"=>"[]",
    "skills"=>"", "projects"=>"[]", "company"=>"", "position"=>"",
    "experience_date"=>"", "responsibilities"=>"", "certifications"=>"",
    "achievements"=>"", "languages"=>"", "template"=>"template1", "photo"=>""
];

if ($isEdit) {
    $stmt = $conn->prepare("SELECT * FROM resumes WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $found = $stmt->get_result()->fetch_assoc();
    if (!$found) { die("Resume not found."); }
    $data = $found;
}

$education = json_decode($data["education"], true);
if (!is_array($education) || count($education) === 0) {
    $education = [["degree"=>"","college"=>"","year"=>"","percentage"=>""]];
}
$projects = json_decode($data["projects"], true);
if (!is_array($projects) || count($projects) === 0) {
    $projects = [["title"=>"","technology"=>"","description"=>""]];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $isEdit ? "Edit" : "Create"; ?> Resume</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="topbar">
  <div>
    <h1><?php echo $isEdit ? "Edit Resume" : "Create Resume"; ?></h1>
    <p>Fill in your details and preview your CV</p>
  </div>
  <a class="btn" href="../index.html">← Dashboard</a>
</header>

<main class="builder">
  <form id="resumeForm" class="form-panel" action="../actions/save_resume.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo (int)$data["id"]; ?>">

    <section class="form-card">
      <h2>1. Personal Information</h2>
      <label>Full Name *</label>
      <input name="name" id="name" required value="<?php echo htmlspecialchars($data["name"]); ?>">

      <label for="photo">Profile Photo</label>
      <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/gif,image/webp">
      <p class="help">JPG, PNG, GIF, or WebP; maximum 5 MB.</p>
      <?php if (!empty($data["photo"])): ?>
      <label class="photo-remove"><input type="checkbox" name="remove_photo" id="removePhoto" value="1"> Remove current photo</label>
      <?php endif; ?>

      <div class="two-col">
        <div><label>Phone</label><input name="phone" id="phone" value="<?php echo htmlspecialchars($data["phone"]); ?>"></div>
        <div><label>Email</label><input type="email" name="email" id="email" value="<?php echo htmlspecialchars($data["email"]); ?>"></div>
      </div>

      <label>Address</label>
      <textarea name="address" id="address"><?php echo htmlspecialchars($data["address"]); ?></textarea>

      <div class="two-col">
        <div><label>LinkedIn</label><input name="linkedin" id="linkedin" value="<?php echo htmlspecialchars($data["linkedin"]); ?>"></div>
        <div><label>GitHub</label><input name="github" id="github" value="<?php echo htmlspecialchars($data["github"]); ?>"></div>
      </div>

      <label>Career Objective / Profile Summary</label>
      <textarea name="objective" id="objective"><?php echo htmlspecialchars($data["objective"]); ?></textarea>
    </section>

    <section class="form-card">
      <h2>2. Education</h2>
      <div id="educationContainer">
        <?php foreach ($education as $e): ?>
        <div class="repeat-item education-item">
          <input name="degree[]" class="degree" placeholder="Degree / Course" value="<?php echo htmlspecialchars($e["degree"] ?? ""); ?>">
          <input name="college[]" class="college" placeholder="College / University" value="<?php echo htmlspecialchars($e["college"] ?? ""); ?>">
          <div class="two-col">
            <input name="year[]" class="year" placeholder="Year" value="<?php echo htmlspecialchars($e["year"] ?? ""); ?>">
            <input name="percentage[]" class="percentage" placeholder="CGPA / Percentage" value="<?php echo htmlspecialchars($e["percentage"] ?? ""); ?>">
          </div>
          <button type="button" class="btn danger small remove-row">Remove</button>
        </div>
        <?php endforeach; ?>
      </div>
      <button type="button" class="btn success" id="addEducation">+ Add Education</button>
    </section>

    <section class="form-card">
      <h2>3. Skills</h2>
      <textarea name="skills" id="skills" placeholder="HTML, CSS, JavaScript, PHP, Python"><?php echo htmlspecialchars($data["skills"]); ?></textarea>
    </section>

    <section class="form-card">
      <h2>4. Projects</h2>
      <div id="projectContainer">
        <?php foreach ($projects as $p): ?>
        <div class="repeat-item project-item">
          <input name="project_title[]" class="project-title" placeholder="Project Title" value="<?php echo htmlspecialchars($p["title"] ?? ""); ?>">
          <input name="project_technology[]" class="project-tech" placeholder="Technologies Used" value="<?php echo htmlspecialchars($p["technology"] ?? ""); ?>">
          <textarea name="project_description[]" class="project-description" placeholder="Project Description"><?php echo htmlspecialchars($p["description"] ?? ""); ?></textarea>
          <button type="button" class="btn danger small remove-row">Remove</button>
        </div>
        <?php endforeach; ?>
      </div>
      <button type="button" class="btn success" id="addProject">+ Add Project</button>
    </section>

    <section class="form-card">
      <h2>5. Work Experience</h2>
      <div class="two-col">
        <div><label>Company</label><input name="company" id="company" value="<?php echo htmlspecialchars($data["company"]); ?>"></div>
        <div><label>Position</label><input name="position" id="position" value="<?php echo htmlspecialchars($data["position"]); ?>"></div>
      </div>
      <label>Duration</label><input name="experience_date" id="experience_date" value="<?php echo htmlspecialchars($data["experience_date"]); ?>">
      <label>Responsibilities</label><textarea name="responsibilities" id="responsibilities"><?php echo htmlspecialchars($data["responsibilities"]); ?></textarea>
    </section>

    <section class="form-card">
      <h2>6. Additional Information</h2>
      <label>Certifications</label><textarea name="certifications" id="certifications"><?php echo htmlspecialchars($data["certifications"]); ?></textarea>
      <label>Achievements</label><textarea name="achievements" id="achievements"><?php echo htmlspecialchars($data["achievements"]); ?></textarea>
      <label>Languages</label><input name="languages" id="languages" value="<?php echo htmlspecialchars($data["languages"]); ?>">
    </section>

    <section class="form-card">
      <h2>7. Choose Template</h2>
      <select name="template" id="template">
        <option value="template1" <?php echo $data["template"]==="template1"?"selected":""; ?>>Professional</option>
        <option value="template2" <?php echo $data["template"]==="template2"?"selected":""; ?>>Modern</option>
        <option value="template3" <?php echo $data["template"]==="template3"?"selected":""; ?>>Simple</option>
      </select>
    </section>

    <div class="form-actions">
      <button class="btn primary" type="submit">Save Resume</button>
      <button class="btn" type="button" id="refreshPreview">Update Preview</button>
      <a class="btn" href="../index.html">Cancel</a>
    </div>
  </form>

  <aside class="preview-panel">
    <div class="preview-heading">
      <h2>Live Preview</h2>
      <span>A4 style</span>
    </div>
    <div id="resumePreview" class="resume <?php echo htmlspecialchars($data["template"]); ?>">
      <div class="cv-header">
        <div class="cv-photo-wrap">
          <img class="profile-photo" id="previewPhoto" src="<?php echo !empty($data["photo"]) ? "../" . htmlspecialchars($data["photo"], ENT_QUOTES, "UTF-8") : ""; ?>" alt="Profile photo" style="display:<?php echo !empty($data["photo"]) ? "block" : "none"; ?>">
          <div class="fallback-photo" id="previewAvatar" style="display:<?php echo !empty($data["photo"]) ? "none" : "flex"; ?>"><?php echo strtoupper(substr($data["name"] ?: "Y", 0, 1)); ?></div>
        </div>
        <div class="cv-head-text">
          <h1 id="previewName"><?php echo htmlspecialchars($data["name"] ?: "Your Name"); ?></h1>
          <p id="previewContact"><?php echo htmlspecialchars(trim($data["phone"]." | ".$data["email"], " |")); ?></p>
          <p id="previewAddress"><?php echo htmlspecialchars($data["address"]); ?></p>
          <p id="previewLinks"><?php echo htmlspecialchars(trim($data["linkedin"]." | ".$data["github"], " |")); ?></p>
        </div>
      </div>

      <div class="cv-section" id="profileSection"><h2>Profile</h2><p id="previewObjective"><?php echo nl2br(htmlspecialchars($data["objective"])); ?></p></div>
      <div class="cv-section" id="educationSection"><h2>Education</h2><div id="previewEducation"></div></div>
      <div class="cv-section" id="skillsSection"><h2>Skills</h2><div id="previewSkills"></div></div>
      <div class="cv-section" id="projectsSection"><h2>Projects</h2><div id="previewProjects"></div></div>
      <div class="cv-section" id="experienceSection"><h2>Work Experience</h2><div id="previewExperience"></div></div>
      <div class="cv-section" id="certificationsSection"><h2>Certifications</h2><p id="previewCertifications"></p></div>
      <div class="cv-section" id="achievementsSection"><h2>Achievements</h2><p id="previewAchievements"></p></div>
      <div class="cv-section" id="languagesSection"><h2>Languages</h2><p id="previewLanguages"></p></div>
    </div>
  </aside>
</main>

<script src="../assets/js/script.js"></script>
</body>
</html>