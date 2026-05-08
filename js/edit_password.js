// Password Editing Elements
const passwordSection = document.getElementById("password-section");
const passwordSectionEditing = document.getElementById(
  "password-section-editing"
);
const editPasswordBtn = document.getElementById("edit-password-btn");
const savePasswordBtn = document.getElementById("save-password-btn");
const cancelPasswordBtn = document.getElementById("cancel-password-btn");
const passwordInput = document.getElementById("password-input");

// Show password editing section
editPasswordBtn.addEventListener("click", () => {
  passwordSection.style.display = "none";
  passwordSectionEditing.style.display = "grid";
});

// Hide password editing section
cancelPasswordBtn.addEventListener("click", () => {
  passwordSectionEditing.style.display = "none";
  passwordSection.style.display = "grid";
});

// Save new password
savePasswordBtn.addEventListener("click", () => {
  const newPassword = passwordInput.value.trim();

  if (!newPassword) {
    alert("Password cannot be empty.");
    return;
  }

  // Simulate saving to the database with an AJAX request
  fetch("../database/update_password.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ password: newPassword }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Password updated successfully!");
        passwordSectionEditing.style.display = "none"; // Hide editing section
        passwordSection.style.display = "grid"; // Show password section
      } else {
        alert(data.message || "Failed to save password. Please try again.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred while saving.");
    });
});
