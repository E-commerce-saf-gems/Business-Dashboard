document.addEventListener('DOMContentLoaded', () => {
    const receivablesBtn = document.getElementById('receivablesBtn');
    const payablesBtn = document.getElementById('payablesBtn');
    const receivablesTable = document.getElementById('receivablesTable');
    const payablesTable = document.getElementById('payablesTable');
    const receivablesFilters = document.getElementById('receivablesFilters');
    const payablesFilters = document.getElementById('payablesFilters');

    // Default: Show Receivables and hide Payables
    receivablesTable.classList.remove('hidden');
    payablesTable.classList.add('hidden');
    receivablesFilters.classList.remove('hidden');
    payablesFilters.classList.add('hidden');
    receivablesBtn.classList.add('active');
    payablesBtn.classList.remove('active');

    // Toggle between Receivables and Payables
    receivablesBtn.addEventListener('click', () => {
        receivablesTable.classList.remove('hidden');
        payablesTable.classList.add('hidden');
        receivablesFilters.classList.remove('hidden');
        payablesFilters.classList.add('hidden');
        receivablesBtn.classList.add('active');
        payablesBtn.classList.remove('active');
    });

    payablesBtn.addEventListener('click', () => {
        payablesTable.classList.remove('hidden');
        receivablesTable.classList.add('hidden');
        payablesFilters.classList.remove('hidden');
        receivablesFilters.classList.add('hidden');
        payablesBtn.classList.add('active');
        receivablesBtn.classList.remove('active');
    });

    // Initialize Charts
    const salesChartCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesChartCtx, {
        type: 'bar',
        data: {
            labels: ['Receivables', 'Payables'],
            datasets: [{
                label: 'Amount',
                data: [2543, 2132], // Example data
                backgroundColor: ['#007bff', '#dc3545'],
                borderColor: ['#0056b3', '#c82333'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const cashFlowChartCtx = document.getElementById('cashFlowChart').getContext('2d');
    const cashFlowChart = new Chart(cashFlowChartCtx, {
        type: 'pie',
        data: {
            labels: ['Receivables', 'Payables'],
            datasets: [{
                data: [2543, 2132], // Example data
                backgroundColor: ['#28a745', '#ffc107'],
                borderColor: ['#218838', '#e0a800'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return tooltipItem.label + ': $' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
});