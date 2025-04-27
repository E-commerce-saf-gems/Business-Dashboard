// sales.js

// ------------ SALES OVERVIEW PAGE (list page) ------------

// Function to handle delete confirmation
function setupDeleteButtons() {
    const deleteButtons = document.querySelectorAll(".delete-btn");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const confirmed = confirm("Are you sure you want to delete this item?");
            if (confirmed) {
                const row = button.closest("tr");
                if (row) {
                    row.remove();
                }

                // Optional: Make AJAX request to delete from database
                // fetch('deleteItem.php?id=ITEM_ID', { method: 'POST' });
            }
        });
    });
}

// Function to load sales overview summary
function loadSalesOverview() {
    const viewFilter = document.getElementById("viewFilter");
    const selectedFilter = viewFilter ? viewFilter.value.toLowerCase() : "monthly";

    fetch(`getSalesOverview.php?filter=${selectedFilter}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            console.log("Sales overview data:", data);

            const totalEl = document.getElementById("totalSales");
            const settledEl = document.getElementById("totalSettledSales");
            const remainingEl = document.getElementById("totalRemainingSales");

            if (!totalEl || !settledEl || !remainingEl) {
                console.error("One or more sales overview elements are missing in the DOM.");
                return;
            }

            const formatAmount = (amount) => `Rs. ${parseFloat(amount || 0).toLocaleString()}`;

            totalEl.innerText = formatAmount(data.totalSales);
            settledEl.innerText = formatAmount(data.totalSettledSales);
            remainingEl.innerText = formatAmount(data.totalRemainingSales);
        })
        .catch(error => console.error("Error loading sales overview:", error));
}

// ------------ ADD/EDIT SALES FORM PAGE (form page) ------------

// Function to load customers into the customer dropdown
function loadCustomers() {
    fetch('./getCustomerData.php')
        .then(response => response.json())
        .then(data => {
            const emailSelect = document.getElementById('email');
            if (!emailSelect) return;
            data.forEach(customer => {
                const option = document.createElement('option');
                option.value = customer.email;
                option.textContent = customer.email;
                emailSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading customers:', error);
        });
}

// Function to load stones into the stone dropdown
function loadStones() {
    fetch('./getStonesData.php')
        .then(response => response.json())
        .then(data => {
            const stoneSelect = document.getElementById('stone');
            if (!stoneSelect) return;

            // Clear previous options
            stoneSelect.innerHTML = '';

            // Add default 'Select Stone' option
            const defaultOption = document.createElement('option');
            defaultOption.value = ''; // Empty value for no selection
            defaultOption.textContent = 'Select a stone';
            stoneSelect.appendChild(defaultOption);

            // Loop through each stone and add to the dropdown
            data.forEach(stone => {
                const option = document.createElement('option');
                option.value = stone.stone_id; // Use stone_id as the value
                option.textContent = `${stone.stone_id} - ${stone.colour} - ${stone.type} - ${stone.shape} (Carats: ${stone.size}) gem stone`;
                stoneSelect.appendChild(option);
            });

            // Automatically fetch price if there's a default selected stone
            const selectedStoneId = stoneSelect.value;
            if (selectedStoneId) {
                fetchStonePrice(selectedStoneId);
            }
        })
        .catch(error => {
            console.error('Error loading stones:', error);
        });
}

// Function to fetch stone price based on selected stone ID
function fetchStonePrice(stoneId) {
    if (!stoneId) return; // If no stone is selected, return

    console.log('Fetching stone price for ID:', stoneId);

    fetch(`./getStonePrice.php?stone_id=${stoneId}`)
        .then(response => response.json())
        .then(data => {
            const amountField = document.getElementById('amount');
            if (!amountField) return;

            if (data.amount) {
                amountField.value = data.amount; // Fill the amount field with the price
            } else {
                amountField.value = '';
                alert('Selected stone price not found.');
            }
        })
        .catch(error => {
            console.error('Error fetching stone price:', error);
        });
}

// Validate that settled amount is not more than amount
function validateSettledAmount() {
    const amountField = document.getElementById('amount');
    const amountSettledField = document.getElementById('amountSettled');

    if (!amountField || !amountSettledField) return;

    amountSettledField.addEventListener('input', function () {
        const totalAmount = parseFloat(amountField.value) || 0;
        const settledAmount = parseFloat(amountSettledField.value) || 0;

        if (settledAmount > totalAmount) {
            alert('Settled amount cannot exceed the total amount!');
            amountSettledField.value = totalAmount;
        }
    });
}

// ------------ INITIALIZER ------------

document.addEventListener('DOMContentLoaded', function () {
    // If delete buttons exist -> sales list page
    if (document.querySelector(".delete-btn")) {
        setupDeleteButtons();
    }

    // If sales overview fields exist -> sales list page
    if (document.getElementById("totalSales")) {
        loadSalesOverview();

        const viewFilter = document.getElementById("viewFilter");
        if (viewFilter) {
            viewFilter.addEventListener("change", loadSalesOverview);
        }

        // Auto-refresh sales overview every 30 seconds
        setInterval(loadSalesOverview, 30000);
    }

    // If sales form fields exist -> sales form page
    if (document.getElementById("email") && document.getElementById("stone")) {
        loadCustomers();
        loadStones();
        validateSettledAmount();

        const stoneSelect = document.getElementById('stone');
        stoneSelect.addEventListener('change', function () {
            const selectedStoneId = this.value;
            if (selectedStoneId) {
                fetchStonePrice(selectedStoneId);
            } else {
                document.getElementById('amount').value = '';
            }
        });
    }
});









