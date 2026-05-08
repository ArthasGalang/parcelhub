const editBtn = document.getElementById("edit-btn");
const saveBtn = document.getElementById("save-btn");
const cancelBtn = document.getElementById("cancel-btn");
const nameSection = document.getElementById("name-section");
const nameSectionEditing = document.getElementById("name-section-editing");
const inputField = document.querySelector(".account-setting-profile_input");
const nameDisplay = document.querySelector(
  "#name-section .account-setting-profile_editor_detail"
);

nameSectionEditing.style.display = "none";

editBtn.addEventListener("click", () => {
  inputField.value = nameDisplay.textContent.trim();

  nameSection.style.display = "none"; // Hide the name section
  nameSectionEditing.style.display = "grid"; // Show the editing section with grid display
});

cancelBtn.addEventListener("click", () => {
  nameSectionEditing.style.display = "none";
  nameSection.style.display = "grid";
});

saveBtn.addEventListener("click", () => {
  const newName = inputField.value.trim();

  if (!newName) {
    alert("Name cannot be empty.");
    return;
  }

  // Simulate saving to the database with an AJAX request
  fetch("../database/update_name.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ name: newName }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Update the displayed name in the #name-section
        nameDisplay.textContent = newName;
        nameSectionEditing.style.display = "none"; // Hide the editing section
        nameSection.style.display = "grid"; // Show the name section
      } else {
        alert(data.message || "Failed to save name. Please try again.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred while saving.");
    });
});
