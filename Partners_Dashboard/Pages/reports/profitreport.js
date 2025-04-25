// Select button: Fetch and fill profit & loss data
document.getElementById("selectDateBtn").addEventListener("click", () => {
    const startDate = document.getElementById("plStartDate").value;
    const endDate = document.getElementById("plEndDate").value;

    if (!startDate || !endDate) {
        alert("Please select a valid date range.");
        return;
    }

    fetch('getProfitLossData.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ startDate, endDate })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("totalSalesRevenue").value = data.totalSalesRevenue || 0;
        document.getElementById("purchases").value = data.purchases || 0;
        document.getElementById("cuttingPolishing").value = data.cuttingPolishing || 0;
        document.getElementById("certificationFees").value = data.certificationFees || 0;
        document.getElementById("marketing").value = data.marketing || 0;
        document.getElementById("logistics").value = data.logistics || 0;
        document.getElementById("otherExpenses").value = data.otherExpenses || 0;

        // Optional: auto-calculate totals here if desired
    })
    .catch(error => console.error("Error fetching data:", error));
});


// Generate Report button: Store data and go to preview page
document.getElementById("generateReportBtn").addEventListener("click", () => {
    const startDate = document.getElementById("plStartDate").value;
    const endDate = document.getElementById("plEndDate").value;

    if (!startDate || !endDate) {
        alert("Please select a date range before generating the report.");
        return;
    }

    const totalSalesRevenue = parseFloat(document.getElementById("totalSalesRevenue").value) || 0;
    const otherIncome = parseFloat(document.getElementById("otherIncome")?.value) || 0;
    const purchases = parseFloat(document.getElementById("purchases").value) || 0;
    const cuttingPolishing = parseFloat(document.getElementById("cuttingPolishing").value) || 0;
    const certificationFees = parseFloat(document.getElementById("certificationFees").value) || 0; // Add this lines = parseFloat(document.getElementById("rentUtilities").value) || 0;
    const marketing = parseFloat(document.getElementById("marketing").value) || 0;
    const logistics = parseFloat(document.getElementById("logistics").value) || 0;
    const otherExpenses = parseFloat(document.getElementById("otherExpenses").value) || 0;

    const totalExpenses = cuttingPolishing + certificationFees + marketing + logistics + otherExpenses;
    const grossProfit = totalSalesRevenue - purchases; // No COGS logic yet
    const netProfit = grossProfit + otherIncome - totalExpenses;

    const reportData = {
        dateRange: `From ${startDate} to ${endDate}`,
        totalSalesRevenue,
        otherIncome,
        purchases,
        cuttingPolishing,
        certificationFees,
        marketing,
        logistics,
        otherExpenses,
        totalExpenses,
        grossProfit,
        netProfit,
        inventoryOpening: 0,
        inventoryClosing: 0
    };

    localStorage.setItem("reportType", "profit-loss");
    localStorage.setItem("profitLossReportData", JSON.stringify(reportData));

    window.location.href = "Profitlosspreview.html";
});

