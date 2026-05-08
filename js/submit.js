document.addEventListener("DOMContentLoaded", () => {
    const addItemBtn = document.querySelector(".add-item-btn");
    const itemListContainer = document.querySelector("#item-list-container");
    const saveBtn = document.querySelector("#save-btn");
    const itemListTotalContainer = document.querySelector(".item-list_total_container");
    const itemListCount = document.querySelector(".item-list_count span");
    const itemListTotalValue = document.querySelector(".item-list_total_value");

    let itemCount = 0;
    let totalValue = 0;
    let formattedCurrency = "US$";

    // Function to add a new item form
    function addNewItemForm() {
        const template = document.querySelector(".add-item-container");
        const newContainer = template.cloneNode(true);
        newContainer.style.display = "flex"; // Show the new container
        newContainer.querySelectorAll("input, select").forEach(input => input.value = ""); // Reset values

        newContainer.querySelector(".remove-item-btn").addEventListener("click", () => {
            newContainer.remove();
            validateInputs();
        });

        itemListContainer.appendChild(newContainer);
        validateInputs();
    }

    // Validate all item inputs before enabling save button
    function validateInputs() {
        const allItemsValid = Array.from(document.querySelectorAll(".add-item-container"))
            .filter(container => container.style.display === "flex")
            .every(container => Array.from(container.querySelectorAll(".add-item-product_input"))
                .every(input => input.value.trim() !== ""));

        saveBtn.disabled = !allItemsValid;
        saveBtn.style.backgroundColor = allItemsValid ? "orange" : "#e5e5e5";
    }

    // Save all items and update summary
    function saveAllItems() {
        const allContainers = document.querySelectorAll(".add-item-container");
        itemCount = 0;
        totalValue = 0;

        allContainers.forEach(container => {
            if (container.style.display === "flex") {
                const productType = container.querySelector('select[name="product_type[]"]').value;
                const productName = container.querySelector('input[name="product_name[]"]').value;
                const productQuantity = parseInt(container.querySelector('input[name="product_quantity[]"]').value, 10);
                const unitPrice = parseFloat(container.querySelector('input[name="product_price[]"]').value);
                const newItemPrice = productQuantity * unitPrice;

                console.log(`Saving Item: ${productType} - ${productName} x ${productQuantity} @ ${unitPrice}`);

                itemCount++;
                totalValue += newItemPrice;
            }
        });

        itemListCount.textContent = `${itemCount} Items`;
        itemListTotalValue.textContent = `${formattedCurrency} ${totalValue.toFixed(2)}`;
        itemListTotalContainer.style.display = itemCount > 0 ? "flex" : "none";
    }

    // Event Listeners
    addItemBtn.addEventListener("click", addNewItemForm);
    saveBtn.addEventListener("click", saveAllItems);

    // Initial validation call
    validateInputs();
});
