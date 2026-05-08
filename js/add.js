const inputs = document.querySelectorAll(".text-field_input");
const submitButton = document.querySelector(".button-address-submit_text");
const submitButtonText = document.querySelector(
  ".button-address-submit_text_2"
);

function checkInputs() {
  const allFilled = Array.from(inputs).every(
    (input) => input.value.trim() !== ""
  );
  submitButton.disabled = !allFilled;
  submitButton.classList.toggle("active", allFilled);
  submitButtonText.classList.toggle("active", allFilled);
}

inputs.forEach((input) => input.addEventListener("input", checkInputs));
