document.addEventListener("DOMContentLoaded", function () {
  const warehouseSelect = document.querySelector(
    ".text-field_select_warehouse"
  );
  const totalValueElement = document.querySelector(".item-list_total_value");
  const itemCountElement = document.querySelector(".item-list_count span");

  const currencyMap = {
    Canada: { code: "CAD", symbol: "C$" },
    "United Kingdom": { code: "GBP", symbol: "£" },
    "United States": { code: "USD", symbol: "$" },
    Japan: { code: "JPY", symbol: "¥" },
    Australia: { code: "AUD", symbol: "A$" },
    "South Korea": { code: "KRW", symbol: "₩" },
    China: { code: "CNY", symbol: "¥" },
    Taiwan: { code: "TWD", symbol: "NT$" },
  };

  let totalValue = 0; // Initialize the total value in USD

  // Example function to fetch conversion rate from USD to the selected currency
  async function getConversionRate(targetCurrency) {
    const apiUrl = `https://api.exchangerate-api.com/v4/latest/USD`;
    const response = await fetch(apiUrl);
    const data = await response.json();
    const rate = data.rates[targetCurrency];
    return rate;
  }

  // Function to update the currency based on selected warehouse
  async function updateCurrency() {
    const selectedWarehouse = warehouseSelect.value;
    const country = selectedWarehouse.split(", ")[1]; // Extract the country part from the label

    if (currencyMap[country]) {
      const selectedCurrency = currencyMap[country].code;
      const currencySymbol = currencyMap[country].symbol;
      const conversionRate = await getConversionRate(selectedCurrency);

      // Update the currency symbol and total value
      const formattedTotalValue = (totalValue * conversionRate).toFixed(2);
      totalValueElement.textContent = `${currencySymbol} ${formattedTotalValue}`;

      // Update the item count (you could track item counts in a similar manner)
      itemCountElement.textContent = `${totalValue} Items`; // This is just an example, modify as needed
    }
  }

  // Event listener for changes in warehouse selection
  warehouseSelect.addEventListener("change", updateCurrency);

  // Initial call to set the currency when the page loads
  updateCurrency();
});
