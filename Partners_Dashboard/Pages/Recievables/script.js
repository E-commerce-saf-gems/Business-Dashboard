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
        .catch(error => console.error('Error fetching customers:', error));

    // Fetch stones when a customer is selected
    customerDropdown.addEventListener('change', function () {
        const customerId = this.value;

        // Clear existing stones
        stoneDropdown.innerHTML = '<option value="">Select a Stone</option>';

        if (customerId) {
            fetch(`./getStones.php?customer_id=${customerId}`)
                .then(response => response.json())
                .then(stones => {
                    stones.forEach(stone => {
                        const option = document.createElement('option');
                        option.value = stone.stone_id;
                        option.textContent = `${stone.type} (Carats: ${stone.weight}) (Amount To Be Settled: Rs.${stone.amountToBeSettled})` ;
                        stoneDropdown.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching stones:', error));
        }
    });
});

setTimeout(function() {
    const message = document.querySelector(".success-message");
    if (message) {
        message.style.display = "none";
    }
}, 5000);