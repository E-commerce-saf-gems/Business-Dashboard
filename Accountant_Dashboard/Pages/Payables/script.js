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
    const buyerDropdown = document.getElementById('buyer');
    const stoneDropdown = document.getElementById('stone');

    // Fetch buyers
    fetch('./getBuyers.php')
        .then(response => response.json())
        .then(buyers => {
            buyers.forEach(buyer => {
                const option = document.createElement('option');
                option.value = buyer.buyer_id;
                option.textContent = buyer.email;
                buyerDropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching buyers:', error));

    // Fetch stones when a buyer is selected
    buyerDropdown.addEventListener('change', function () {
        const buyerId = this.value;

        // Clear existing stones
        stoneDropdown.innerHTML = '<option value="">Select a Stone</option>';

        if (buyerId) {
            fetch(`./getStones.php?buyer_id=${buyerId}`)
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