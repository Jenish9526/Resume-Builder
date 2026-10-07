document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("resumeForm");
  if (!form) return;

  const educationContainer = document.getElementById("educationContainer");
  const projectContainer = document.getElementById("projectContainer");

  document.getElementById("addEducation").addEventListener("click", () => {
    const item = document.createElement("div");
    item.className = "repeat-item education-item";
    item.innerHTML = `
            <input name="degree[]" class="degree" placeholder="Degree / Course">
            <input name="college[]" class="college" placeholder="College / University">
            <div class="two-col">
                <input name="year[]" class="year" placeholder="Year">
                <input name="percentage[]" class="percentage" placeholder="CGPA / Percentage">
            </div>
            <button type="button" class="btn danger small remove-row">Remove</button>`;
    educationContainer.appendChild(item);
  });

  document.getElementById("addProject").addEventListener("click", () => {
    const item = document.createElement("div");
    item.className = "repeat-item project-item";
    item.innerHTML = `
            <input name="project_title[]" class="project-title" placeholder="Project Title">
            <input name="project_technology[]" class="project-tech" placeholder="Technologies Used">
            <textarea name="project_description[]" class="project-description" placeholder="Project Description"></textarea>
            <button type="button" class="btn danger small remove-row">Remove</button>`;
    projectContainer.appendChild(item);
  });

  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("remove-row")) {
      const parent = e.target.closest(".repeat-item");
      const count =
        e.target.closest("#educationContainer, #projectContainer")?.children
          .length || 0;
      if (count > 1) parent.remove();
      else alert("At least one entry should remain.");
      updatePreview();
    }
  });

  const photoInput = document.getElementById("photo");
  if (photoInput) {
    photoInput.addEventListener("change", (e) => {
      const file = e.target.files[0];
      if (!file) return;
      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
      if (!allowedTypes.includes(file.type)) {
        photoInput.value = "";
        return alert("Please select a JPG, PNG, GIF, or WebP image.");
      }
      if (file.size > 5 * 1024 * 1024) {
        photoInput.value = "";
        return alert("The photo must be 5 MB or smaller.");
      }
      const reader = new FileReader();
      reader.onload = (ev) => {
        const previewPhoto = document.getElementById("previewPhoto");
        const previewAvatar = document.getElementById("previewAvatar");
        if (previewPhoto) {
          previewPhoto.src = ev.target.result;
          previewPhoto.style.display = "block";
        }
        if (previewAvatar) previewAvatar.style.display = "none";
      };
      reader.readAsDataURL(file);
      const removePhoto = document.getElementById("removePhoto");
      if (removePhoto) removePhoto.checked = false;
    });
  }

  const removePhoto = document.getElementById("removePhoto");
  if (removePhoto) {
    const previewPhoto = document.getElementById("previewPhoto");
    const previewAvatar = document.getElementById("previewAvatar");
    const savedPhotoSrc = previewPhoto ? previewPhoto.getAttribute("src") : "";
    removePhoto.addEventListener("change", () => {
      if (removePhoto.checked) {
        if (previewPhoto) {
          previewPhoto.removeAttribute("src");
          previewPhoto.style.display = "none";
        }
        if (previewAvatar) previewAvatar.style.display = "flex";
        if (photoInput) photoInput.value = "";
      } else if (savedPhotoSrc) {
        if (previewPhoto) {
          previewPhoto.src = savedPhotoSrc;
          previewPhoto.style.display = "block";
        }
        if (previewAvatar) previewAvatar.style.display = "none";
      }
    });
  }

  form.addEventListener("input", updatePreview);
  document.getElementById("template").addEventListener("change", updatePreview);
  document
    .getElementById("refreshPreview")
    .addEventListener("click", updatePreview);

  function setText(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = value || "";
  }

  function showSection(id, show) {
    const el = document.getElementById(id);
    if (el) el.style.display = show ? "" : "none";
  }

  function updatePreview() {
    const name = document.getElementById("name").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const email = document.getElementById("email").value.trim();
    const address = document.getElementById("address").value.trim();
    const linkedin = document.getElementById("linkedin").value.trim();
    const github = document.getElementById("github").value.trim();
    const objective = document.getElementById("objective").value.trim();

    setText("previewName", name || "Your Name");
    setText("previewAvatar", name ? name.charAt(0).toUpperCase() : "Y");
    setText("previewContact", [phone, email].filter(Boolean).join(" | "));
    setText("previewAddress", address);
    setText("previewLinks", [linkedin, github].filter(Boolean).join(" | "));
    setText("previewObjective", objective);
    showSection("profileSection", !!objective);

    let educationHTML = "";
    document.querySelectorAll(".education-item").forEach((item) => {
      const degree = item.querySelector(".degree")?.value.trim();
      const college = item.querySelector(".college")?.value.trim();
      const year = item.querySelector(".year")?.value.trim();
      const percentage = item.querySelector(".percentage")?.value.trim();
      if (degree || college || year || percentage) {
        educationHTML += `<div class="cv-entry"><h3>${escapeHTML(degree)}</h3>
                <p>${escapeHTML(college)}</p><p>${escapeHTML(year)}${percentage ? " | " + escapeHTML(percentage) : ""}</p></div>`;
      }
    });
    document.getElementById("previewEducation").innerHTML = educationHTML;
    showSection("educationSection", !!educationHTML);

    const skills = document.getElementById("skills").value;
    let skillsHTML = "";
    skills.split(/[,;\n]+/).forEach((skill) => {
      skill = skill.trim();
      if (skill) skillsHTML += `<span>${escapeHTML(skill)}</span>`;
    });
    document.getElementById("previewSkills").innerHTML = skillsHTML;
    showSection("skillsSection", !!skillsHTML);

    let projectHTML = "";
    document.querySelectorAll(".project-item").forEach((item) => {
      const title = item.querySelector(".project-title")?.value.trim();
      const tech = item.querySelector(".project-tech")?.value.trim();
      const desc = item.querySelector(".project-description")?.value.trim();
      if (title || tech || desc) {
        projectHTML += `<div class="cv-entry"><h3>${escapeHTML(title)}</h3>
                ${tech ? `<p><strong>Technologies:</strong> ${escapeHTML(tech)}</p>` : ""}
                <p>${escapeHTML(desc)}</p></div>`;
      }
    });
    document.getElementById("previewProjects").innerHTML = projectHTML;
    showSection("projectsSection", !!projectHTML);

    const company = document.getElementById("company").value.trim();
    const position = document.getElementById("position").value.trim();
    const experienceDate = document
      .getElementById("experience_date")
      .value.trim();
    const responsibilities = document
      .getElementById("responsibilities")
      .value.trim();

    document.getElementById("previewExperience").innerHTML =
      `<div class="cv-entry"><h3>${escapeHTML(position)}</h3>
             <p><strong>${escapeHTML(company)}</strong>${experienceDate ? " | " + escapeHTML(experienceDate) : ""}</p>
             <p>${escapeHTML(responsibilities)}</p></div>`;
    showSection(
      "experienceSection",
      !!(company || position || experienceDate || responsibilities),
    );

    const certifications = document
      .getElementById("certifications")
      .value.trim();
    const achievements = document.getElementById("achievements").value.trim();
    const languages = document.getElementById("languages").value.trim();

    setText("previewCertifications", certifications);
    setText("previewAchievements", achievements);
    setText("previewLanguages", languages);

    showSection("certificationsSection", !!certifications);
    showSection("achievementsSection", !!achievements);
    showSection("languagesSection", !!languages);

    const resume = document.getElementById("resumePreview");
    resume.className = "resume " + document.getElementById("template").value;
  }

  function escapeHTML(value) {
    return String(value || "").replace(
      /[&<>"']/g,
      (ch) =>
        ({
          "&": "&amp;",
          "<": "&lt;",
          ">": "&gt;",
          '"': "&quot;",
          "'": "&#039;",
        })[ch],
    );
  }

  updatePreview();
});
