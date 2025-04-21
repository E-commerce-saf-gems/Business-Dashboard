document.getElementById("fetchSalesDataBtn").addEventListener("click", () => {
    const startDate = document.getElementById("salesStartDate").value;
    const endDate = document.getElementById("salesEndDate").value;

    if (!startDate || !endDate) {
        alert("Please select both start and end dates.");
        return;
    }

    fetch("getSalesData.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ startDate, endDate }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }

        // Extracting data
        const totalSalesAmount = data.totalSalesAmount || 0;
        const totalSettledAmount = data.totalSettledAmount || 0;
        const totalRemainingAmount = data.totalRemainingAmount || 0;
        const itemsSold = data.itemsSold || 0;

        // Store the data in localStorage
        localStorage.setItem("reportType", "sales");
        localStorage.setItem("salesReportData", JSON.stringify({
            totalSalesAmount: totalSalesAmount,
            totalSettledAmount: totalSettledAmount,
            totalRemainingAmount: totalRemainingAmount,
            itemsSold: itemsSold,
            dateRange: `From ${startDate} to ${endDate}`
        }));

        // Navigate to salespreview.html
        window.location.href = "salespreview.html";
    })
    .catch(error => {
        console.error("Error fetching sales data:", error);
    });
});



