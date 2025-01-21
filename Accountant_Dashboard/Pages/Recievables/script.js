// Function to handle delete confirmation
document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".delete-btn");
    
    deleteButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const confirmed = confirm("Are you sure you want to delete this item?");
            
            if (confirmed) {
                // Here you would add code to delete the item from the database
                // For now, just remove the row from the table
                const row = button.closest("tr");
                row.remove();
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const customerDropdown = document.getElementById('customer');
    const stoneDropdown = document.getElementById('stone');
    const amountInput = document.getElementById('amount');
    const amountError = document.createElement('span');
    amountError.classList.add('error-message');
    amountInput.parentNode.appendChild(amountError);

    // Fetch customers
    fetch('./getCustomers.php')
        .then(response => response.json())
        .then(customers => {
            customers.forEach(customer => {
                const option = document.createElement('option');
                option.value = customer.customer_id;
                option.textContent = customer.email;
                customerDropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching buyers:', error));

    // Fetch stones when a buyer is selected
    customerDropdown.addEventListener('change', function () {
        const customerId = this.value;

        // Clear existing stones
        stoneDropdown.innerHTML = '<option value="">Select a Stone</option>';
        amountInput.removeAttribute('max');
        amountError.textContent = ''; // Clear error

        if (customerId) {
            fetch(`./getStones.php?customer_id=${customerId}`)
                .then(response => response.json())
                .then(stones => {
                    stones.forEach(stone => {
                        const option = document.createElement('option');
                        option.value = stone.stone_id;
                        option.textContent = `${stone.type} (Carats: ${stone.size}) (Amount To Be Settled: Rs.${stone.amountToBeSettled})`;
                        option.dataset.amountToBeSettled = stone.amountToBeSettled; // Store the value in a dataset
                        stoneDropdown.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching stones:', error));
        }
    });

    // Update max value for amount when a stone is selected
    stoneDropdown.addEventListener('change', function () {
        const selectedOption = stoneDropdown.options[stoneDropdown.selectedIndex];
        const amountToBeSettled = selectedOption.dataset.amountToBeSettled;

        if (amountToBeSettled) {
            amountInput.setAttribute('max', amountToBeSettled);
            amountError.textContent = ''; // Clear error
        } else {
            amountInput.removeAttribute('max');
        }
    });

    // Validate amount input
    amountInput.addEventListener('input', function () {
        const max = parseFloat(amountInput.getAttribute('max'));
        const value = parseFloat(amountInput.value);

        if(value < 0){
            amountError.textContent = 'Amount cannot negative value';
        }
        else if (max && value > max) {
            amountError.textContent = `Amount cannot exceed amount to be settled: Rs. ${max}`;
        } else {
            amountError.textContent = '';
        }
    });
});

// Auto-hide success messages after 5 seconds
setTimeout(function() {
    const message = document.querySelector(".success-message");
    if (message) {
        message.style.display = "none";
    }
}, 5000);

//*******FILTER********
function filterTransactions() {
    const date = document.getElementById('date-filter').value;
    const customer = document.getElementById('customer-filter').value;

    // Prepare the URL with filter parameters
    let url = 'transactions.php?';
    if (date) url += `date=${encodeURIComponent(date)}&`;
    if (customer) url += `customer=${encodeURIComponent(customer)}&`;

    // Fetch filtered data from server(send the request to the server)
    fetch(url)
        .then(response => response.text())
        .then(data => {
            // Update the table body with filtered data
            document.getElementById('transaction-body').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
}