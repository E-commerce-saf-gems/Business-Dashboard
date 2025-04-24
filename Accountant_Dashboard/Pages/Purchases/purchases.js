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

    function loadPurchasesOverview() {
        const selectedFilter = viewFilter ? viewFilter.value.toLowerCase() : "monthly";

        fetch(`getPurchasesOverview.php?filter=${selectedFilter}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                console.log("Purchases overview data:", data);

                const totalEl = document.getElementById("totalPurchases");
                const settledEl = document.getElementById("totalSettledPurchases");
                const remainingEl = document.getElementById("totalRemainingPurchases");

                if (!totalEl || !settledEl || !remainingEl) {
                    console.error("One or more Purchases overview elements are missing in the DOM.");
                    return;
                }

                const formatAmount = (amount) => `Rs. ${parseFloat(amount || 0).toLocaleString()}`;

                totalEl.innerText = formatAmount(data.totalPurchases);
                settledEl.innerText = formatAmount(data.totalSettledPurchases);
                remainingEl.innerText = formatAmount(data.totalRemainingPurchases);
            })
            .catch(error => console.error("Error loading Purchases overview:", error));
    }

    // Initial load
    loadPurchasesOverview();

    // Reload on filter change if dropdown exists
    if (viewFilter) {
        viewFilter.addEventListener("change", loadPurchasesOverview);
    }

    // Auto-refresh every 30 seconds
    setInterval(loadPurchasesOverview, 30000);
});

