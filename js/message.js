document.addEventListener("DOMContentLoaded", function () {
  const messageDiv = document.getElementById("message");
  if (messageDiv) {
    messageDiv.style.display = "block";
    setTimeout(() => {
      messageDiv.style.display = "none";
    }, 5000);
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const togglePassword = document.getElementById("togglePassword");
  const toggleConfirmPassword = document.getElementById(
    "toggleConfirmPassword"
  );
  const passwordField = document.getElementById("password");
  const confirmPasswordField = document.getElementById("confirm_password");

  if (togglePassword && passwordField) {
    togglePassword.addEventListener("click", function () {
      const type =
        passwordField.getAttribute("type") === "password" ? "text" : "password";
      passwordField.setAttribute("type", type);

      const icon = togglePassword.querySelector("box-icon");
      if (icon) {
        icon.setAttribute("name", type === "password" ? "hide" : "show");
      }
    });
  }

  if (toggleConfirmPassword && confirmPasswordField) {
    toggleConfirmPassword.addEventListener("click", function () {
      const type =
        confirmPasswordField.getAttribute("type") === "password"
          ? "text"
          : "password";
      confirmPasswordField.setAttribute("type", type);

      const icon = toggleConfirmPassword.querySelector("box-icon");
      if (icon) {
        icon.setAttribute("name", type === "password" ? "hide" : "show");
      }
    });
  }
});

