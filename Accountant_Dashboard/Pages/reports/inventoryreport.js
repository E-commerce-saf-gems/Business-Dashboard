// Fetch inventory data and store it
document.getElementById("selectDateBtn").addEventListener("click", () => {
    const startDate = document.getElementById("inventoryStartDate").value;
    const endDate = document.getElementById("inventoryEndDate").value;

    if (!startDate || !endDate) {
        alert("Please select both start and end dates.");
        return;
    }

    fetch("getInventoryData.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ startDate, endDate }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Server error: " + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }

        const totalItems = data.totalItems || 0;
        const totalBidItems = data.totalBidItems || 0;
        const totalInventoryValue = data.totalInventoryValue || 0;
        const breakdown = data.breakdown || [];

        // Save the report data in localStorage (no redirect here)
        localStorage.setItem("reportType", "inventory");
        localStorage.setItem("inventoryReportData", JSON.stringify({
            totalItems,
            totalBidItems,
            totalInventoryValue,
            breakdown,
            dateRange: `From ${startDate} to ${endDate}`
        }));

        // ✅ Fill form fields after fetching
        document.getElementById("totalItems").value = totalItems;
        document.getElementById("totalBidItems").value = totalBidItems;
        document.getElementById("totalInventoryValue").value = totalInventoryValue.toFixed(2);
        document.getElementById("reportPeriod").value = `From ${startDate} to ${endDate}`;

        
    })
    .catch(error => {
        console.error("Error fetching inventory data:", error);
        alert("Failed to fetch inventory data. Please try again.");
    });
});

// Redirect only when user clicks Generate Report button
document.getElementById("generateReportBtn").addEventListener("click", () => {
    const reportData = localStorage.getItem("inventoryReportData");

    if (reportData) {
        window.location.href = "inventorypreview.html";
    } else {
        alert("Please fetch the inventory data first by clicking 'Fetch Inventory Data'.");
    }
});






