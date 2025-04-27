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
});

    document.addEventListener("DOMContentLoaded", function () {
        // Only try to attach if button exists
        const emailInput = document.getElementById('customer-email');
        const selectCustomerBtn = document.getElementById('select-customer');
        const nicInput = document.getElementById('nic');
        const nameInput = document.getElementById('customer-name');
        const stoneDropdown = document.getElementById('stone');
        const amountInput = document.getElementById('amount');
        const customerIdHidden = document.getElementById('customer_id');
    
        // Validation
        if (!emailInput || !selectCustomerBtn || !nicInput || !nameInput || !stoneDropdown || !amountInput || !customerIdHidden) {
            console.error("Required fields missing on page!");
            return;
        }
    
        const amountError = document.createElement("span");
        amountError.classList.add("error-message");
        amountInput.parentNode.appendChild(amountError);
    
        // When clicking 'Select' button
        selectCustomerBtn.addEventListener('click', function () {
            const email = emailInput.value.trim();
            if (!email) {
                alert('Please enter a customer email.');
                return;
            }
    
            // Clear previous
            nicInput.value = '';
            nameInput.value = '';
            customerIdHidden.value = '';
            stoneDropdown.innerHTML = '<option value="">Select a Stone</option>';
            amountInput.removeAttribute('max');
            amountError.textContent = '';
    
            // Fetch customer by email
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
                    customerIdHidden.value = customer.customer_id;
    
                    // Now fetch stones
                    fetch(`./getStones.php?customer_id=${customer.customer_id}`)
                        .then(response => response.json())
                        .then(stones => {
                            if (Array.isArray(stones) && stones.length > 0) {
                                stones.forEach(stone => {
                                    const option = document.createElement('option');
                                    option.value = stone.stone_id;
                                    option.textContent = `${stone.type} (Carats: ${stone.size}) (Amount To Be Settled: Rs.${stone.amountToBeSettled})`;
                                    option.dataset.amountToBeSettled = stone.amountToBeSettled;
                                    stoneDropdown.appendChild(option);
                                });
                            } else {
                                const noStonesMessage = document.createElement('option');
                                noStonesMessage.textContent = 'No stones available for this customer to settle total amount.';
                                noStonesMessage.disabled = true;
                                stoneDropdown.appendChild(noStonesMessage);
                            }
                        })
                        .catch(error => console.error('Error fetching stones:', error));
                } else {
                    alert(customerData.message || 'Customer not found.');
                }
            })
            .catch(error => console.error('Error fetching customer:', error));
        });
    
        // When stone selected
        stoneDropdown.addEventListener("change", function () {
            const selectedOption = stoneDropdown.options[stoneDropdown.selectedIndex];
            const amountToBeSettled = selectedOption.dataset.amountToBeSettled;
    
            if (amountToBeSettled) {
                amountInput.setAttribute("max", amountToBeSettled);
                amountError.textContent = '';
            } else {
                amountInput.removeAttribute("max");
            }
        });
    
        // Validate amount input
        amountInput.addEventListener("input", function () {
            const max = parseFloat(amountInput.getAttribute("max"));
            const value = parseFloat(amountInput.value);
    
            if (value < 0 ) {
                amountError.textContent = "❌ Error: Amount cannot be a negative value";
            
            }else if (max && value > max) {
                amountError.textContent = `❌ Error: Amount cannot exceed Rs. ${max}`;
            } else {
                amountError.textContent = "";
            }
        });
    });
    
    // Auto-hide success messages
    setTimeout(() => {
        const successMessage = document.querySelector('.success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000);
    


