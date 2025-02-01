let selectedReportType = '';

// Open modal for the selected report type
function openModal(reportType) {
    selectedReportType = reportType;
    document.getElementById('reportModal').style.display = 'block';
}

// Close the modal
function closeModal() {
    document.getElementById('reportModal').style.display = 'none';
}

// Show/hide custom date range inputs and month/year selectors based on the selected period
document.getElementById('time-period').addEventListener('change', function () {
    const period = this.value;
    const monthOptions = document.getElementById('month-options');
    const yearOptions = document.getElementById('year-options');
    const customDateRange = document.getElementById('custom-date-range');

    // Hide all options initially
    monthOptions.style.display = 'none';
    yearOptions.style.display = 'none';
    customDateRange.style.display = 'none';

    // Show options based on selected period
    if (period === 'monthly') {
        monthOptions.style.display = 'block';
        yearOptions.style.display = 'block'; // Show year selector for monthly
        populateYears();  // Populate the years dropdown
    } else if (period === 'yearly') {
        yearOptions.style.display = 'block';
        populateYears();  // Populate the years dropdown
    } else if (period === 'custom') {
        customDateRange.style.display = 'block';
    }
});

// Function to populate the years dropdown with the last 10 years
function populateYears() {
    const currentYear = new Date().getFullYear();
    const yearDropdown = document.getElementById('year');
    yearDropdown.innerHTML = ''; // Clear existing options

    for (let i = currentYear - 10; i <= currentYear; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i;
        yearDropdown.appendChild(option);
    }
}

// Generate the report and redirect to the preview page
function generateReport() {
    const period = document.getElementById('time-period').value;
    const today = new Date();
    let startDate = '';
    let endDate = today.toISOString().split('T')[0]; // Default end date as today's date

    // Calculate start and end dates based on the period
    if (period === 'daily') {
        const yesterday = new Date();
        yesterday.setDate(today.getDate() - 1);
        startDate = yesterday.toISOString().split('T')[0];
    } else if (period === 'weekly') {
        const lastWeek = new Date();
        lastWeek.setDate(today.getDate() - 7);
        startDate = lastWeek.toISOString().split('T')[0];
    } else if (period === 'monthly') {
        const selectedMonth = document.getElementById('month').value;
        const selectedYear = document.getElementById('year').value;
        startDate = `${selectedYear}-${selectedMonth}-01`; // Start date as the first day of the selected month
        endDate = new Date(selectedYear, selectedMonth, 0).toISOString().split('T')[0]; // Last day of the selected month
    } else if (period === 'yearly') {
        const selectedYear = document.getElementById('year').value;
        startDate = `${selectedYear}-01-01`; // Start date as the first day of the selected year
        endDate = `${selectedYear}-12-31`; // End date as the last day of the selected year
    } else if (period === 'custom') {
        startDate = document.getElementById('start-date').value;
        endDate = document.getElementById('end-date').value;
    }

    // Construct the URL with parameters
    const url = `./${selectedReportType}preview.html?period=${period}&start=${startDate}&end=${endDate}`;

    // Redirect to the preview page
    window.location.href = url;
}













