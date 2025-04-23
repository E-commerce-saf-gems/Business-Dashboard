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

// Function to open report in a new window and pass data via localStorage
function openReportInNewWindow(reportType) {
    let previewPage;
    if (reportType === "profit-loss") {
        previewPage = "profitpreview.html";
    } else if (reportType === "sales") {
        previewPage = "salespreview.html";
    } else if (reportType === "inventory") {
        previewPage = "inventorypreview.html";
    }
    localStorage.setItem("reportType", reportType);
    window.open(previewPage);
}


// Generate Profit and Loss Report and open in new window
function generateProfitLossReport() {
    const reportData = {
        dateRange: `${document.getElementById("plStartDate").value} to ${document.getElementById("plEndDate").value}`,
        totalSalesRevenue: parseFloat(document.getElementById("totalSalesRevenue").value) || 0,
        otherIncome: parseFloat(document.getElementById("otherIncome").value) || 0,
        inventoryOpening: parseFloat(document.getElementById("inventoryOpening").value) || 0,
        purchases: parseFloat(document.getElementById("purchases").value) || 0,
        inventoryClosing: parseFloat(document.getElementById("inventoryClosing").value) || 0,
        salaries: parseFloat(document.getElementById("salaries").value) || 0,
        rentUtilities: parseFloat(document.getElementById("rentUtilities").value) || 0,
        marketing: parseFloat(document.getElementById("marketing").value) || 0,
        adminExpenses: parseFloat(document.getElementById("adminExpenses").value) || 0
    };

    // Store data in localStorage
    localStorage.setItem("profitLossReportData", JSON.stringify(reportData));
    openReportInNewWindow("profit-loss");
}

// Generate Sales Report and open in new window
function generateSalesReport() {
    const reportData = {
        dateRange: `${document.getElementById("salesStartDate").value} to ${document.getElementById("salesEndDate").value}`,
        gemType: document.getElementById("gemType").value || "All Types",
        totalSales: parseInt(document.getElementById("totalSales").value) || 0,
        totalRevenue: parseFloat(document.getElementById("totalRevenue").value) || 0,
        avgSaleAmount: parseFloat(document.getElementById("avgSaleAmount").value) || 0,
        unitsSold: parseInt(document.getElementById("unitsSold").value) || 0,
        revenueByType: parseFloat(document.getElementById("revenueByType").value) || 0,
        auctionUnits: parseInt(document.getElementById("auctionUnits").value) || 0,
        auctionRevenue: parseFloat(document.getElementById("auctionRevenue").value) || 0,
        regularUnits: parseInt(document.getElementById("regularUnits").value) || 0,
        regularRevenue: parseFloat(document.getElementById("regularRevenue").value) || 0,
        newCustomerSales: parseInt(document.getElementById("newCustomerSales").value) || 0,
        repeatCustomerSales: parseInt(document.getElementById("repeatCustomerSales").value) || 0,
        avgSalesNew: parseFloat(document.getElementById("avgSalesNew").value) || 0,
        avgSalesRepeat: parseFloat(document.getElementById("avgSalesRepeat").value) || 0
    };

    // Store data in localStorage
    localStorage.setItem("salesReportData", JSON.stringify(reportData));
    openReportInNewWindow("sales");
}

// Generate Inventory Report and open in new window
function generateInventoryReport() {
    const reportData = {
        reportDate: document.getElementById("inventoryDate").value,
        totalInventoryValue: parseFloat(document.getElementById("totalInventoryValue").value) || 0,
        gemTypeBreakdown: document.getElementById("gemTypeBreakdown").value || "Various Types",
        currentQuantity: parseInt(document.getElementById("currentQuantity").value) || 0,
        valuePerGemType: parseFloat(document.getElementById("valuePerGemType").value) || 0,
        itemsSold: parseInt(document.getElementById("itemsSold").value) || 0,
        newAcquisitions: parseInt(document.getElementById("newAcquisitions").value) || 0,
        avgDaysInventory: parseInt(document.getElementById("avgDaysInventory").value) || 0,
        percentOlder30: parseFloat(document.getElementById("percentOlder30").value) || 0,
        percentOlder60: parseFloat(document.getElementById("percentOlder60").value) || 0,
        percentOlder90: parseFloat(document.getElementById("percentOlder90").value) || 0
    };

    // Store data in localStorage
    localStorage.setItem("inventoryReportData", JSON.stringify(reportData));
    openReportInNewWindow("inventory");
}



//report preview logic
document.addEventListener("DOMContentLoaded", function () {
    const reportType = localStorage.getItem("reportType");
    const reportTemplate = document.getElementById("reportTemplate");

    if (reportType === "profit-loss") {
        const data = JSON.parse(localStorage.getItem("profitLossReportData"));
        
        reportTemplate.innerHTML = `
        <div class="report-container">
            <img src="../../../images/logo.png" alt="Company Logo" class="company-logo">
            <p class="company-name">SAF Gems</p>
            <h3 class="report-title">Profit & Loss Report</h3>
            <p><strong>Prepared by:</strong>Saman Kumara</p>
            <p><strong>Period:</strong> ${data.dateRange}</p>
            <p class="financial-details">Financial statements in Sri Lankan Rupees</p>

            <table class="report-table">
                <!-- Revenue Section -->
                <tr class="section-header"><th colspan="2">REVENUE</th></tr>
                <tr><td>Total Sales Revenue</td><td>$${data.totalSalesRevenue.toFixed(2)}</td></tr>
                <tr><td>Add: Other Income</td><td>$0.00</td></tr> <!-- Placeholder for sales returns -->
                <tr class="section-total"><td>Total Revenue</td><td>$${data.totalSalesRevenue.toFixed(2)}</td></tr>
                
                <!-- Cost of Goods Sold Section -->
                <tr class="section-header"><th colspan="2">COST OF GOODS SOLD</th></tr>
                <tr><td>Inventory Opening Value</td><td>$${data.inventoryOpening.toFixed(2)}</td></tr>
                <tr><td>Add: Purchases</td><td>$${data.purchases.toFixed(2)}</td></tr>
                <tr><td>Inventory Available</td><td>$${(data.inventoryOpening + data.purchases).toFixed(2)}</td></tr>
                <tr><td>Less: Inventory Closing Value</td><td>$${data.inventoryClosing.toFixed(2)}</td></tr>
                <tr class="section-total"><td>Cost of Goods Sold</td><td>$${(data.inventoryOpening + data.purchases - data.inventoryClosing).toFixed(2)}</td></tr>
                
                <!-- Gross Profit Section -->
                <tr class="section-total"><td>Gross Profit</td><td>$${(data.totalSalesRevenue - (data.inventoryOpening + data.purchases - data.inventoryClosing)).toFixed(2)}</td></tr>
                
                <!-- Expenses Section -->
                <tr class="section-header"><th colspan="2">EXPENSES</th></tr>
                <tr><td>Salaries & Wages</td><td>$${data.salaries.toFixed(2)}</td></tr>
                <tr><td>Rent & Utilities</td><td>$${data.rentUtilities.toFixed(2)}</td></tr>
                <tr><td>Advertising & Marketing</td><td>$${data.marketing.toFixed(2)}</td></tr>
                <tr><td>Administrative Expenses</td><td>$${data.adminExpenses.toFixed(2)}</td></tr>
                <tr class="section-total"><td>Total Expenses</td><td>$${(data.marketing + data.salaries + data.adminExpenses + data.rentUtilities).toFixed(2)}</td></tr>
                
                <!-- Net Operating Income Section -->
                <tr class="section-total"><td>Net Operating Profit</td><td>$${(data.totalSalesRevenue - (data.inventoryOpening + data.purchases - data.inventoryClosing) - (data.marketing + data.salaries + data.adminExpenses)).toFixed(2)}</td></tr>
                
                <!-- Net Income Section -->
                <tr class="section-header"><th colspan="2">NET INCOME</th></tr>
                <tr class="section-total"><td>Net Profit (or Loss)</td><td>$${((data.totalSalesRevenue - (data.inventoryOpening + data.purchases - data.inventoryClosing) - (data.marketing + data.salaries + data.adminExpenses)) + data.otherIncome).toFixed(2)}</td></tr>
            </table>
        </div>
        `;
    } 
    else if (reportType === "sales") {
        const data = JSON.parse(localStorage.getItem("salesReportData"));
    
        reportTemplate.innerHTML = `
        <div class="report-container">
            <img src="../../../images/logo.png" alt="Company Logo" class="company-logo">
            <p class="company-name">SAF Gems</p>
            <h3 class="report-title">Sales Report</h3>
            <p><strong>Prepared by:</strong> Quinn Campbell</p>
            <p><strong>Period:</strong> ${data.dateRange}</p>
            <p class="financial-details">Sales details for the selected period</p>
    
            <table class="report-table">
                <!-- Sales Summary Section -->
                <tr class="section-header"><th colspan="2">SALES SUMMARY</th></tr>
                <tr><td>Total Sales</td><td>$${data.totalSales.toFixed(2)}</td></tr>
                <tr><td>Total Revenue</td><td>$${data.totalRevenue.toFixed(2)}</td></tr>
                <tr><td>Average Sale Amount</td><td>$${data.avgSaleAmount.toFixed(2)}</td></tr>
                <tr><td>Units Sold</td><td>${data.unitsSold}</td></tr>
                <tr class="section-total"><td>Total Revenue</td><td>$${data.totalRevenue.toFixed(2)}</td></tr>
                
                <!-- Revenue by Type Section -->
                <tr class="section-header"><th colspan="2">REVENUE BY TYPE</th></tr>
                <tr><td>Revenue from Auction Sales</td><td>$${data.auctionRevenue.toFixed(2)}</td></tr>
                <tr><td>Revenue from Regular Sales</td><td>$${data.regularRevenue.toFixed(2)}</td></tr>
                <tr class="section-total"><td>Total Revenue by Type</td><td>$${(data.auctionRevenue + data.regularRevenue).toFixed(2)}</td></tr>
                
                <!-- Customer Sales Section -->
                <tr class="section-header"><th colspan="2">CUSTOMER SALES</th></tr>
                <tr><td>New Customer Sales</td><td>${data.newCustomerSales}</td></tr>
                <tr><td>Repeat Customer Sales</td><td>${data.repeatCustomerSales}</td></tr>
                <tr class="section-total"><td>Total Customer Sales</td><td>${(data.newCustomerSales + data.repeatCustomerSales)}</td></tr>
                
                <!-- Average Sales per Customer -->
                <tr class="section-header"><th colspan="2">AVERAGE SALES</th></tr>
                <tr><td>Average Sale (New Customers)</td><td>$${data.avgSalesNew.toFixed(2)}</td></tr>
                <tr><td>Average Sale (Repeat Customers)</td><td>$${data.avgSalesRepeat.toFixed(2)}</td></tr>
            </table>
        </div>
        `;
    }
    

    else if (reportType === "inventory") {
        const data = JSON.parse(localStorage.getItem("inventoryReportData"));
    
        reportTemplate.innerHTML = `
        <div class="report-container">
            <img src="../../../images/logo.png" alt="Company Logo" class="company-logo">
            <p class="company-name">SAF Gems</p>
            <h3 class="report-title">Inventory Report</h3>
            <p><strong>Prepared by:</strong> Quinn Campbell</p>
            <p><strong>Report Date:</strong> ${data.reportDate}</p>
            <p class="financial-details">Inventory status and breakdown for the selected period</p>
    
            <table class="report-table">
                <!-- Inventory Summary Section -->
                <tr class="section-header"><th colspan="2">INVENTORY SUMMARY</th></tr>
                <tr><td>Total Inventory Value</td><td>$${data.totalInventoryValue.toFixed(2)}</td></tr>
                <tr><td>Current Quantity</td><td>${data.currentQuantity}</td></tr>
                <tr><td>Value per Gem Type</td><td>$${data.valuePerGemType.toFixed(2)}</td></tr>
                <tr><td>Items Sold</td><td>${data.itemsSold}</td></tr>
                <tr class="section-total"><td>Total Inventory Value</td><td>$${data.totalInventoryValue.toFixed(2)}</td></tr>
    
                <!-- Acquisition and Breakdown Section -->
                <tr class="section-header"><th colspan="2">ACQUISITION & BREAKDOWN</th></tr>
                <tr><td>New Acquisitions</td><td>${data.newAcquisitions}</td></tr>
                <tr><td>Gem Type Breakdown</td><td>${data.gemTypeBreakdown}</td></tr>
    
                <!-- Inventory Ageing Section -->
                <tr class="section-header"><th colspan="2">INVENTORY AGEING</th></tr>
                <tr><td>Inventory Older than 30 Days</td><td>${data.percentOlder30}%</td></tr>
                <tr><td>Inventory Older than 60 Days</td><td>${data.percentOlder60}%</td></tr>
                <tr><td>Inventory Older than 90 Days</td><td>${data.percentOlder90}%</td></tr>
    
                <!-- Average Days in Inventory -->
                <tr class="section-header"><th colspan="2">INVENTORY AGE DETAILS</th></tr>
                <tr><td>Average Days in Inventory</td><td>${data.avgDaysInventory} days</td></tr>
            </table>
            
        </div>
        `;
    }
    
});






