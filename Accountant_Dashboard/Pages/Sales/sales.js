// Function to handle delete confirmation
document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".delete-btn");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const confirmed = confirm("Are you sure you want to delete this item?");
            if (confirmed) {
                // Remove row from the table
                const row = button.closest("tr");
                row.remove();

                // Optional: Add AJAX call here to delete from the database
                // Example:
                // fetch('deleteItem.php?id=ITEM_ID', { method: 'POST' });
            }
        });
    });

    const viewFilter = document.getElementById("viewFilter");

    function loadSalesOverview() {
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

    // Initial load
    loadSalesOverview();

    // Reload on filter change if dropdown exists
    if (viewFilter) {
        viewFilter.addEventListener("change", loadSalesOverview);
    }

    // Auto-refresh every 30 seconds
    setInterval(loadSalesOverview, 30000);
});





