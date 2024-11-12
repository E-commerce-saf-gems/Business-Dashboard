// reports.js

// Data storage for report details
let reportDetails = [];

// Show the selected report form and hide others
function showReportForm() {
    const reportType = document.getElementById("reportType").value;
    document.getElementById("profitLossForm").style.display = "none";
    document.getElementById("salesForm").style.display = "none";
    document.getElementById("inventoryForm").style.display = "none";

    if (reportType === "profit-loss") {
        document.getElementById("profitLossForm").style.display = "block";
    } else if (reportType === "sales") {
        document.getElementById("salesForm").style.display = "block";
    } else if (reportType === "inventory") {
        document.getElementById("inventoryForm").style.display = "block";
    }
}

//Function to generate consolidated report preview
function generateReport() {
    const reportType = document.getElementById('reportType').value;
    const reportTemplate = document.getElementById('reportTemplate');
    let content = `<h2>${reportType} - Preview</h2><ul>`;
    reportDetails.forEach(detail => {
        content += `<li>${detail.date} - ${detail.category}: ${detail.description} ($${detail.amount})</li>`;
    });
    content += `</ul>`;
    reportTemplate.innerHTML = content;
}


// Generate Profit and Loss Report Preview
function generateProfitLossReport() {
    const startDate = document.getElementById("plStartDate").value;
    const endDate = document.getElementById("plEndDate").value;

    // Revenue Section Inputs
    const totalSalesRevenue = parseFloat(document.getElementById("totalSalesRevenue").value) || 0;
    const otherIncome = parseFloat(document.getElementById("otherIncome").value) || 0;
    const grossRevenue = totalSalesRevenue + otherIncome;

    // COGS Section Inputs
    const inventoryOpening = parseFloat(document.getElementById("inventoryOpening").value) || 0;
    const purchases = parseFloat(document.getElementById("purchases").value) || 0;
    const inventoryClosing = parseFloat(document.getElementById("inventoryClosing").value) || 0;
    const totalCOGS = inventoryOpening + purchases - inventoryClosing;

    // Gross Profit Calculation
    const grossProfit = grossRevenue - totalCOGS;

    // Operating Expenses Inputs
    const salaries = parseFloat(document.getElementById("salaries").value) || 0;
    const rentUtilities = parseFloat(document.getElementById("rentUtilities").value) || 0;
    const marketing = parseFloat(document.getElementById("marketing").value) || 0;
    const adminExpenses = parseFloat(document.getElementById("adminExpenses").value) || 0;
    const totalOperatingExpenses = salaries + rentUtilities + marketing + adminExpenses;

    // Net Profit Calculation
    const netProfit = grossProfit - totalOperatingExpenses;

    // Inject the report content into the modal
    const reportTemplate = document.getElementById("reportTemplate");
    reportTemplate.innerHTML = `<h3>Profit and Loss Report</h3>
                                <p>Date Range: ${startDate} to ${endDate}</p>
                                <h4>Revenue</h4>
                                <ul>
                                    <li>Total Sales Revenue: $${totalSalesRevenue.toFixed(2)}</li>
                                    <li>Other Income: $${otherIncome.toFixed(2)}</li>
                                    <li><strong>Gross Revenue: $${grossRevenue.toFixed(2)}</strong></li>
                                </ul>

                                <h4>Cost of Goods Sold (COGS)</h4>
                                <ul>
                                    <li>Inventory Opening Value: $${inventoryOpening.toFixed(2)}</li>
                                    <li>Purchases: $${purchases.toFixed(2)}</li>
                                    <li>Inventory Closing Value: $${inventoryClosing.toFixed(2)}</li>
                                    <li><strong>Total COGS: $${totalCOGS.toFixed(2)}</strong></li>
                                </ul>

                                <h4>Gross Profit</h4>
                                <p><strong>$${grossProfit.toFixed(2)}</strong></p>

                                <h4>Operating Expenses</h4>
                                <ul>
                                    <li>Salaries and Wages: $${salaries.toFixed(2)}</li>
                                    <li>Rent and Utilities: $${rentUtilities.toFixed(2)}</li>
                                    <li>Marketing and Advertising: $${marketing.toFixed(2)}</li>
                                    <li>Administrative Expenses: $${adminExpenses.toFixed(2)}</li>
                                    <li><strong>Total Operating Expenses: $${totalOperatingExpenses.toFixed(2)}</strong></li>
                                </ul>

                                <h4>Net Profit</h4>
                                <p><strong>$${netProfit.toFixed(2)}</strong></p>`;

    // Display the modal
    document.getElementById("reportModal").style.display = "block";
}

// Generate Sales Report Preview
function generateSalesReport() {
    const startDate = document.getElementById("salesStartDate").value;
    const endDate = document.getElementById("salesEndDate").value;
    const gemType = document.getElementById("gemType").value || "All Types";

    // Total Sales Summary Section
    const totalSales = parseInt(document.getElementById("totalSales").value) || 0;
    const totalRevenue = parseFloat(document.getElementById("totalRevenue").value) || 0;
    const avgSaleAmount = parseFloat(document.getElementById("avgSaleAmount").value) || 0;

    // Sales by Gem Type Section
    const unitsSold = parseInt(document.getElementById("unitsSold").value) || 0;
    const revenueByType = parseFloat(document.getElementById("revenueByType").value) || 0;

    // Auction vs Regular Sales Section
    const auctionUnits = parseInt(document.getElementById("auctionUnits").value) || 0;
    const auctionRevenue = parseFloat(document.getElementById("auctionRevenue").value) || 0;
    const regularUnits = parseInt(document.getElementById("regularUnits").value) || 0;
    const regularRevenue = parseFloat(document.getElementById("regularRevenue").value) || 0;

    // Sales by Customer Segment Section
    const newCustomerSales = parseInt(document.getElementById("newCustomerSales").value) || 0;
    const repeatCustomerSales = parseInt(document.getElementById("repeatCustomerSales").value) || 0;
    const avgSalesNew = parseFloat(document.getElementById("avgSalesNew").value) || 0;
    const avgSalesRepeat = parseFloat(document.getElementById("avgSalesRepeat").value) || 0;

    // Inject the report content into the modal
    const salesReportTemplate = document.getElementById("salesReportTemplate");
    salesReportTemplate.innerHTML = `<h3>Sales Report</h3>
                                     <p>Date Range: ${startDate} to ${endDate}</p>
                                     <p>Gem Type: ${gemType}</p>

                                     <h4>Total Sales Summary</h4>
                                     <ul>
                                         <li>Total Number of Sales: ${totalSales}</li>
                                         <li>Total Revenue Generated: $${totalRevenue.toFixed(2)}</li>
                                         <li>Average Sale Amount: $${avgSaleAmount.toFixed(2)}</li>
                                     </ul>

                                     <h4>Sales by Gem Type</h4>
                                     <ul>
                                         <li>Units Sold: ${unitsSold}</li>
                                         <li>Total Revenue: $${revenueByType.toFixed(2)}</li>
                                     </ul>

                                     <h4>Auction vs. Regular Sales</h4>
                                     <ul>
                                         <li>Auction Sales - Units Sold: ${auctionUnits}</li>
                                         <li>Auction Sales Revenue: $${auctionRevenue.toFixed(2)}</li>
                                         <li>Regular Sales - Units Sold: ${regularUnits}</li>
                                         <li>Regular Sales Revenue: $${regularRevenue.toFixed(2)}</li>
                                     </ul>

                                     <h4>Sales by Customer Segment</h4>
                                     <ul>
                                         <li>New Customers - Sales: ${newCustomerSales}</li>
                                         <li>Repeat Customers - Sales: ${repeatCustomerSales}</li>
                                         <li>Average Sales (New Customers): $${avgSalesNew.toFixed(2)}</li>
                                         <li>Average Sales (Repeat Customers): $${avgSalesRepeat.toFixed(2)}</li>
                                     </ul>`;

    // Display the modal
    document.getElementById("salesReportModal").style.display = "block";
}

// Generate Inventory Report Preview
function generateInventoryReport() {
    const reportDate = document.getElementById("inventoryDate").value;
    
    // Total Inventory Value Section
    const totalInventoryValue = parseFloat(document.getElementById("totalInventoryValue").value) || 0;

    // Gem Type Breakdown Section
    const gemTypeBreakdown = document.getElementById("gemTypeBreakdown").value || "Various Types";
    const currentQuantity = parseInt(document.getElementById("currentQuantity").value) || 0;
    const valuePerGemType = parseFloat(document.getElementById("valuePerGemType").value) || 0;
    const totalValuePerType = currentQuantity * valuePerGemType;

    // Sales and Acquisitions Summary Section
    const itemsSold = parseInt(document.getElementById("itemsSold").value) || 0;
    const newAcquisitions = parseInt(document.getElementById("newAcquisitions").value) || 0;
    const netChangeInventory = newAcquisitions - itemsSold;

    // Inventory Aging Section
    const avgDaysInventory = parseInt(document.getElementById("avgDaysInventory").value) || 0;
    const percentOlder30 = parseFloat(document.getElementById("percentOlder30").value) || 0;
    const percentOlder60 = parseFloat(document.getElementById("percentOlder60").value) || 0;
    const percentOlder90 = parseFloat(document.getElementById("percentOlder90").value) || 0;

    // Inject the report content into the modal
    const inventoryReportTemplate = document.getElementById("inventoryReportTemplate");
    inventoryReportTemplate.innerHTML = `<h3>Inventory Report</h3>
                                         <p>As of Date: ${reportDate}</p>

                                         <h4>Total Inventory Value</h4>
                                         <p><strong>Total Inventory Value: $${totalInventoryValue.toFixed(2)}</strong></p>

                                         <h4>Gem Type Breakdown</h4>
                                         <ul>
                                             <li>Gem Type: ${gemTypeBreakdown}</li>
                                             <li>Current Quantity: ${currentQuantity}</li>
                                             <li>Value per Gem Type: $${valuePerGemType.toFixed(2)}</li>
                                             <li><strong>Total Value per Type: $${totalValuePerType.toFixed(2)}</strong></li>
                                         </ul>

                                         <h4>Sales and Acquisitions Summary</h4>
                                         <ul>
                                             <li>Items Sold: ${itemsSold}</li>
                                             <li>New Acquisitions: ${newAcquisitions}</li>
                                             <li><strong>Net Change in Inventory: ${netChangeInventory}</strong></li>
                                         </ul>

                                         <h4>Inventory Aging</h4>
                                         <ul>
                                             <li>Average Days in Inventory: ${avgDaysInventory} days</li>
                                             <li>Percentage Older than 30 Days: ${percentOlder30}%</li>
                                             <li>Percentage Older than 60 Days: ${percentOlder60}%</li>
                                             <li>Percentage Older than 90 Days: ${percentOlder90}%</li>
                                         </ul>`;
    
    // Display the modal
    document.getElementById("inventoryReportModal").style.display = "block";
}

// Close the Inventory Report Modal
function closeInventoryModal() {
    document.getElementById("inventoryReportModal").style.display = "none";
}

// Optional: Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById("inventoryReportModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}



// Close the Sales Report Modal
function closeSalesModal() {
    document.getElementById("salesReportModal").style.display = "none";
}

// Optional: Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById("salesReportModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


// Close the modal
function closeModal() {
    document.getElementById("reportModal").style.display = "none";
}

// Optional: Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById("reportModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Export to PDF using jsPDF
function exportToPDF() {
    const doc = new jsPDF();
    doc.text("Report", 10, 10);
    let line = 20;
    reportDetails.forEach(detail => {
        doc.text(`${detail.date} - ${detail.category}: ${detail.description} ($${detail.amount})`, 10, line);
        line += 10;
    });
    doc.save("report.pdf");
}

// Export to Word using FileSaver.js and Blob
function exportToWord() {
    let content = "Report\n\n";
    reportDetails.forEach(detail => {
        content += `${detail.date} - ${detail.category}: ${detail.description} ($${detail.amount})\n`;
    });
    const blob = new Blob([content], { type: "application/msword" });
    saveAs(blob, "report.doc");
}


// Similarly, update `generateSalesReport` and `generateInventoryReport` to use `openReportInNewWindow`.






