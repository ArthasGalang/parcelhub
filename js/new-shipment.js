document.addEventListener("DOMContentLoaded", () => {
    const addItemBtn = document.querySelector(".add-item-btn");
    const addItemContainer = document.querySelector(".add-item-container");
    const saveBtn = document.querySelector("#save-btn");
    const inputs = document.querySelectorAll(".add-item-product_input");
    const addItemBtnIcon = document.querySelector(".add-item-btn-icon");
    const addItemBtnText = document.querySelector(".add-item-btn-text");
    const itemListContent = document.querySelector(".item-list_content");
    const itemListTotalContainer = document.querySelector(".item-list_total_container");
    const itemListCount = document.querySelector(".item-list_count span");
    const itemListTotalValue = document.querySelector(".item-list_total_value");
    const itemListDetailContainer = document.querySelector(".item-list_detail_container");
    const warehouseSelect = document.querySelector(".text-field_select_warehouse");
    const termsCheckbox = document.querySelector(".terms_checkbox");
    const submitButton = document.querySelector(".button-address-submit_text");
    const trackNoInput = document.querySelector('input[name="track_no"]'); // Track number input

    const currencyMap = {
        Canada: { code: "CAD", symbol: "$" },
        "United Kingdom": { code: "GBP", symbol: "£" },
        "United States": { code: "USD", symbol: "$" },
        Japan: { code: "JPY", symbol: "¥" },
        Australia: { code: "AUD", symbol: "$" },
        "South Korea": { code: "KRW", symbol: "₩" },
        China: { code: "CNY", symbol: "¥" },
        Taiwan: { code: "TWD", symbol: "$" },
    };

    let itemCount = 0;
    let totalValue = 0;
    let formattedCurrency = "US$";
    let editingItem = null;
    let productsArray = [];
    let shipmentProduct = [];

    // Hide the add-item-container and item-list_total_container by default
    addItemContainer.style.display = "none";
    itemListTotalContainer.style.display = "none";

    const resetToDefaultState = () => {
        addItemContainer.style.display = "none";
        addItemBtnIcon.setAttribute("name", "plus");
        addItemBtnText.textContent = "ADD";
        if (itemCount == 0) {
            itemListContent.style.display = "block";
        } else {
            itemListContent.style.display = "none";
        }
        resetInputs();
        editingItem = null;
    };

    const toggleAddItemContainer = () => {
        const isVisible = addItemContainer.style.display === "flex";
        if (isVisible) {
            resetToDefaultState();
        } else {
            addItemContainer.style.display = "flex";
            addItemBtnIcon.setAttribute("name", "minus");
            addItemBtnText.textContent = "CANCEL";
            itemListContent.style.display = "none";
        }
    };

    const validateInputs = () => {
        const allFilled = Array.from(inputs).every((input) => input.value.trim());
        saveBtn.disabled = !allFilled;
        saveBtn.style.backgroundColor = allFilled ? "orange" : "#e5e5e5";
    };

    const resetInputs = () => {
        inputs.forEach((input) => {
            if (input.type === "number" && input.closest("#add-product_quantity")) {
                input.value = "1";
            } else {
                input.value = "";
            }
        });
        validateInputs();
    };

    const removeItem = (iconElement, itemPrice) => {
        const itemDetail = iconElement.closest(".item-list_detail");
        if (itemDetail) {
            const productNameAndQuantity = itemDetail.querySelector(".item-list_detail_main_content_name").textContent;
            const [productName, productQuantity] = productNameAndQuantity.split(" x ");

            // Find the index of the product in the array
            const productIndex = productsArray.findIndex(product =>
                product.name === productName &&
                product.quantity === parseInt(productQuantity, 10) &&
                product.totalPrice === itemPrice
            );

            // Remove the product from the array if found
            if (productIndex > -1) {
                productsArray.splice(productIndex, 1);
                console.log('Updated Products Array after removal:', productsArray);
            }

            // Remove the item from the DOM
            itemDetail.remove();
            itemCount--;
            totalValue -= itemPrice;
            itemListCount.textContent = `${itemCount} Items`;
            itemListTotalValue.textContent = `${formattedCurrency} ${totalValue.toFixed(2)}`;

            if (itemCount === 0) {
                itemListContent.style.display = "block";
                itemListTotalContainer.style.display = "none";
            }

            validateForm(); // Revalidate form after removing item
        }
    };


    const prefillInputs = (itemDetail) => {
        const productType = itemDetail.querySelector(".item-list_detail_main_content_type_product").textContent;
        const productName = itemDetail.querySelector(".item-list_detail_main_content_name").textContent.split(" x ")[0];
        const productQuantity = itemDetail.querySelector(".item-list_detail_main_content_name").textContent.split(" x ")[1];
        const unitPrice = parseFloat(
            itemDetail.querySelector(".item-list_detail_main_price").textContent.replace(/[^\d.-]/g, "")
        );

        document.querySelector("#product-type select").value = productType;
        document.querySelector("#product-name input").value = productName;
        document.querySelector("#product-quantity input").value = productQuantity;
        document.querySelector("#product-quantity ~ div input").value = unitPrice;
    };

    const saveItem = () => {
        const productType = document.querySelector("#product-type select").value;
        const productName = document.querySelector("#product-name input").value;
        const productQuantity = parseInt(document.querySelector("#product-quantity input").value, 10);
        const unitPrice = parseFloat(document.querySelector("#product-quantity ~ div input").value);
        const newItemPrice = productQuantity * unitPrice;

        console.log('Saving Item:');
        console.log(`Product Type: ${productType}`);
        console.log(`Product Name: ${productName}`);
        console.log(`Product Quantity: ${productQuantity}`);
        console.log(`Unit Price: ${unitPrice}`);
        console.log(`New Item Price: ${newItemPrice.toFixed(2)}`);

        const product = {
            type: productType,
            name: productName,
            quantity: productQuantity,
            unitPrice: unitPrice,
            totalPrice: newItemPrice,
        };

        if (editingItem) {
            // Find index of the product being edited in productsArray
            const index = productsArray.findIndex(p =>
                p.name === editingItem.querySelector(".item-list_detail_main_content_name").textContent.split(" x ")[0]
            );

            if (index !== -1) {
                // Update the existing product in productsArray
                totalValue -= productsArray[index].totalPrice; // Subtract old total price
                productsArray[index] = product;
                totalValue += newItemPrice; // Add new total price

                // Update the UI with the new product details
                editingItem.querySelector(".item-list_detail_main_content_type_product").textContent = productType;
                editingItem.querySelector(".item-list_detail_main_content_name").textContent = `${productName} x ${productQuantity}`;
                editingItem.querySelector(".item-list_detail_main_price").textContent = `${formattedCurrency} ${newItemPrice.toFixed(2)}`;
            }
        } else {
            // Add new product to productsArray and the UI
            productsArray.push(product);
            totalValue += newItemPrice;

            const itemDetail = document.createElement("div");
            itemDetail.classList.add("item-list_detail");
            itemDetail.innerHTML = `
            <div class="item-list_detail_img">
                <div class="item-list_detail_icon">
                    <img src="../../assets/product.png" alt="Product">
                </div>
            </div>
            <div class="item-list_detail_main">
                <div class="item-list_detail_main_content">
                    <div class="item-list_detail_main_content_type_product">${productType}</div>
                    <div class="item-list_detail_main_content_name">${productName} x ${productQuantity}</div>
                </div>
                <div class="item-list_detail_main_price">${formattedCurrency} ${newItemPrice.toFixed(2)}</div>
                <div class="item-list_detail_main_action">
                    <box-icon name='trash-alt' class="item-list_detail_main_action_icon"></box-icon>
                </div>
            </div>
        `;

            itemDetail.addEventListener("click", () => {
                editingItem = itemDetail;
                prefillInputs(itemDetail);
                toggleAddItemContainer();
            });

            itemDetail.querySelector(".item-list_detail_main_action_icon").addEventListener("click", (e) => {
                e.stopPropagation();
                removeItem(e.target, newItemPrice);
            });

            itemListDetailContainer.appendChild(itemDetail);
            itemCount++;
        }

        itemListCount.textContent = `${itemCount} Items`;
        itemListTotalValue.textContent = `${formattedCurrency} ${totalValue.toFixed(2)}`;

        resetToDefaultState();
        itemListTotalContainer.style.display = "flex";
        validateForm();
    };

    const updateTotalValue = () => {
        const selectedWarehouse = warehouseSelect.value;
        if (currencyMap[selectedWarehouse]) {
            const { code, symbol } = currencyMap[selectedWarehouse];
            formattedCurrency = `${symbol}`;
            itemListTotalValue.textContent = `${formattedCurrency} ${totalValue.toFixed(2)}`;
        }
    }; // Global array to store form and product data

    const validateForm = () => {
        const isWarehouseSelected = warehouseSelect.value !== "";
        const isItemAdded = itemCount > 0;
        const isTermsChecked = termsCheckbox.checked;
        const isTrackNoFilled = trackNoInput.value.trim() !== "";

        // Create a shipment object with the form data
        const shipmentData = {
            warehouse: warehouseSelect.value,
            trackNumber: trackNoInput.value.trim(),
            termsAccepted: termsCheckbox.checked,
            itemCount: itemCount,
            totalValue: totalValue.toFixed(2),
            products: [...productsArray] // Include products in the shipment data
        };

        // Clear and update the shipmentProduct array with current form data
        shipmentProduct = [shipmentData];

        // Log the form data
        console.log("Form Data:");
        console.log(`Warehouse: ${warehouseSelect.value}`);
        console.log(`Track Number: ${trackNoInput.value}`);
        console.log(`Terms Accepted: ${termsCheckbox.checked}`);
        console.log(`Item Count: ${itemCount}`);
        console.log(`Total Value: ${totalValue.toFixed(2)}`);
        console.log("Products:", productsArray);

        if (isWarehouseSelected && isItemAdded && isTermsChecked && isTrackNoFilled) {
            submitButton.classList.add("active");
            submitButton.style.backgroundColor = "orange";
            submitButton.style.cursor = "pointer";
        } else {
            submitButton.classList.remove("active");
            submitButton.style.backgroundColor = "";
            submitButton.style.cursor = "not-allowed";
        }
    };

    submitButton.addEventListener("click", (e) => {
        e.preventDefault();
        if (submitButton.classList.contains("active")) {
            const shipmentData = shipmentProduct[0];
            console.log("Shipment Data on Submit:", shipmentProduct[0]);

            fetch("../../database/shipment_insert.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(shipmentData), // Sending the first shipment object
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert(data.message || "Shipment saved successfully!");
                        window.location.href = "../../pages/manageOrder.php";
                    } else {
                        alert(data.message || "Failed to save shipment.");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("An error occurred while saving.");
                });
        } else {
            alert("Please complete all required fields before submitting.");
        }
    });



    warehouseSelect.addEventListener("change", () => {
        updateTotalValue();
    });
    termsCheckbox.addEventListener("change", validateForm);
    trackNoInput.addEventListener("input", validateForm);
    addItemBtn.addEventListener("click", toggleAddItemContainer);
    inputs.forEach((input) => input.addEventListener("input", validateInputs));
    saveBtn.addEventListener("click", saveItem);

    validateInputs();
    updateTotalValue();
    validateForm();
});
