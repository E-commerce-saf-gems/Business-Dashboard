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

document.addEventListener("DOMContentLoaded", function () {
    



	const viewFilter = document.getElementById("viewFilter");
    

    function loadExpensesOverview() {
        const selectedFilter = viewFilter.value.toLowerCase(); // Get current filter (monthly, quarterly, yearly)

        fetch(`getExpensesOverview.php?filter=${selectedFilter}`)
            .then(response => response.json()) // ✅ Convert response to JSON here
            .then(data => {
                console.log("Expenses overview data:", data); // ✅ Now log the actual data

                document.getElementById("totalExpenses").innerText = `Rs. ${parseFloat(data.totalExpenses).toLocaleString()}`;
                document.getElementById("totalPaidExpenses").innerText = `Rs. ${parseFloat(data.totalPaidExpenses).toLocaleString()}`;
                document.getElementById("totalPendingExpenses").innerText = `Rs. ${parseFloat(data.totalPendingExpenses).toLocaleString()}`;
                document.getElementById("maxExpenseCategory").innerText = data.maxExpenseCategory || "N/A"; // 🔁 Make sure ID matches HTML
            })
            .catch(error => console.error("Error loading expenses overview:", error));
    }

    // Initial load
    loadExpensesOverview();

    // Reload on filter change
    viewFilter.addEventListener("change", loadExpensesOverview);

    // Auto-refresh every 30 seconds
    setInterval(loadExpensesOverview, 30000);
});



document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.getElementById('stone_id');

    fetch('getStoneDetails.php')
        .then(response => response.json())
        .then(data => {
            dropdown.innerHTML = '<option value="" disabled selected>Select a gem</option>';
            data.forEach(gem => {
                const option = document.createElement('option');
                option.value = gem.stone_id;
                option.textContent = `ID: ${gem.stone_id} | Type: ${gem.type} | Availability: ${gem.availability} | Visibility: ${gem.visibility}`;
                dropdown.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading gem data:', error);
            dropdown.innerHTML = '<option disabled>Error loading gems</option>';
        });
});

