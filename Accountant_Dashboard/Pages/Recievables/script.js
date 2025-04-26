// DELETE confirmation
document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".delete-btn");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const confirmed = confirm("Are you sure you want to delete this item?");
            if (confirmed) {
                const row = button.closest("tr");
                row.remove();
            }
        });
    });

    // FETCH customer and stones
    const emailInput = document.getElementById('customer-email');
    const selectCustomerBtn = document.getElementById('select-customer');
    const nicInput = document.getElementById('nic');
    const nameInput = document.getElementById('customer-name');
    const stoneDropdown = document.getElementById('stone');
    const amountInput = document.getElementById('amount');
    const amountError = document.createElement("span");
    amountError.classList.add("error-message");
    amountInput.parentNode.appendChild(amountError);

    if (!emailInput || !selectCustomerBtn || !nicInput || !nameInput || !stoneDropdown || !amountInput || !amountError) {
        console.error("Some required elements are missing from the page.");
        return;
    }

    selectCustomerBtn.addEventListener('click', function () {
        const email = emailInput.value.trim();

        if (!email) {
            alert('Please enter a customer email.');
            return;
        }

        // Clear previous values
        nicInput.value = '';
        nameInput.value = '';
        stoneDropdown.innerHTML = '<option value="">Select a Stone</option>';
        amountInput.removeAttribute('max');
        amountError.textContent = '';
        document.getElementById('customer_id').value = '';

        // Step 1: Fetch Customer by Email
        fetch('./getCustomers.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `email=${encodeURIComponent(email)}`
        })
            .then(response => response.json())
            .then(customerData => {
                if (customerData.success) {
                    const customer = customerData.customer;
                    nicInput.value = customer.nic;
                    nameInput.value = customer.name;
                    document.getElementById('customer_id').value = customer.customer_id;

                    // Step 2: Fetch Stones by Customer ID
                    fetch(`./getStones.php?customer_id=${customer.customer_id}`)
                        .then(response => response.json())
                        .then(stones => {
                            if (stones.length > 0) {
                                stones.forEach(stone => {
                                    const option = document.createElement('option');
                                    option.value = stone.stone_id;
                                    option.textContent = `${stone.type} (Carats: ${stone.size}) (Amount To Be Settled: Rs.${stone.amountToBeSettled})`;
                                    option.dataset.amountToBeSettled = stone.amountToBeSettled;
                                    stoneDropdown.appendChild(option);
                                });
                            } else {
                                const noStonesMessage = document.createElement('option');
                                noStonesMessage.textContent = 'No stones with pending settlement.';
                                noStonesMessage.disabled = true;
                                stoneDropdown.appendChild(noStonesMessage);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching stones:', error);
                        });

                } else {
                    alert(customerData.message || 'Customer not found.');
                }
            })
            .catch(error => {
                console.error('Error fetching customer details:', error);
            });
    });

    // Update max value for amount when a stone is selected
    stoneDropdown.addEventListener("change", function () {
        const selectedOption = stoneDropdown.options[stoneDropdown.selectedIndex];
        const amountToBeSettled = selectedOption.dataset.amountToBeSettled;

        if (amountToBeSettled) {
            amountInput.setAttribute("max", amountToBeSettled);
            amountError.textContent = ""; // Clear error
        } else {
            amountInput.removeAttribute("max");
        }
    });

    // Validate amount input
    amountInput.addEventListener("input", function () {
        const max = parseFloat(amountInput.getAttribute("max"));
        const value = parseFloat(amountInput.value);

        if (value < 0) {
            amountError.textContent = "Amount cannot be a negative value";
        } else if (max && value > max) {
            amountError.textContent = `Amount cannot exceed Rs. ${max}`;
        } else {
            amountError.textContent = "";
        }
    });
});

// Auto-hide success message
setTimeout(function () {
    const message = document.querySelector(".success-message");
    if (message) {
        message.style.display = "none";
    }
}, 5000);

// FILTER transactions
function filterTransactions() {
    const date = document.getElementById('date-filter').value;
    const customer = document.getElementById('customer-filter').value;

    let url = 'transactions.php?';
    if (date) url += `date=${encodeURIComponent(date)}&`;
    if (customer) url += `customer=${encodeURIComponent(customer)}&`;

    fetch(url)
        .then(response => response.text())
        .then(data => {
            document.getElementById('transaction-body').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
}


