document.getElementById("fetchSalesDataBtn")?.addEventListener("click", () => {
    const startDateInput = document.getElementById("salesStartDate");
    const endDateInput = document.getElementById("salesEndDate");

    if (!startDateInput || !endDateInput) {
        console.error("Start date or end date input not found.");
        return;
    }

    const startDate = startDateInput.value;
    const endDate = endDateInput.value;

    if (!startDate || !endDate) {
        alert("Please select a valid date range.");
        return;
    }

    fetch('./getSalesData.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ startDate, endDate })
    })
    .then(res => res.json())
    .then(data => {
        console.log("Fetched Sales Data:", data);

        const totalSalesAmountInput = document.getElementById("totalSalesAmount");
        const settledAmountInput = document.getElementById("settledAmount");
        const remainingAmountInput = document.getElementById("remainingAmount");
        const itemsSoldInput = document.getElementById("itemsSold");

        if (totalSalesAmountInput) totalSalesAmountInput.value = data.totalSalesAmount || 0;
        if (settledAmountInput) settledAmountInput.value = data.settledAmount || 0;
        if (remainingAmountInput) remainingAmountInput.value = data.remainingAmount || 0;
        if (itemsSoldInput) itemsSoldInput.value = data.itemsSold || 0;

        
    })
    .catch(error => {
        console.error("Error fetching sales data:", error);
        alert("Failed to fetch sales data.");
    });
});

document.getElementById("generateSalesReportBtn")?.addEventListener("click", () => {
    const startDate = document.getElementById("salesStartDate")?.value;
    const endDate = document.getElementById("salesEndDate")?.value;

    if (!startDate || !endDate) {
        alert("Please select a date range before generating the report.");
        return;
    }

    const totalSalesAmount = parseFloat(document.getElementById("totalSalesAmount")?.value) || 0;
    const settledAmount = parseFloat(document.getElementById("settledAmount")?.value) || 0;
    const remainingAmount = parseFloat(document.getElementById("remainingAmount")?.value) || 0;
    const itemsSold = parseInt(document.getElementById("itemsSold")?.value) || 0;

    const reportData = {
        dateRange: `From ${startDate} to ${endDate}`,
        totalSalesAmount,
        settledAmount,
        remainingAmount,
        itemsSold
    };

    localStorage.setItem("reportType", "sales");
    localStorage.setItem("salesReportData", JSON.stringify(reportData));

    window.location.href = "salespreview.html";
});





