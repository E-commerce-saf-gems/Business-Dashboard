const allSideMenu = document.querySelectorAll('#sidebar .side-menu li a');

allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});

const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})


if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}

window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})
document.addEventListener("DOMContentLoaded", function () {
    let salesChart;
    let cashFlowChart;
	let expenseChart;
	let profitChart;
	let revenueVsProfitChart;

    function loadSalesChart() {
        fetch("getSalesChartData.php")
            .then(response => response.json())
            .then(data => {
                if (salesChart) {
                    salesChart.data.labels = data.labels;
                    salesChart.data.datasets[0].data = data.sales;
                    salesChart.update();
                } else {
                    salesChart = new Chart(document.getElementById("salesChart"), {
                        type: "line",
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: "Sales (Rs.)",
                                data: data.sales,
                                borderColor: "rgba(75, 192, 192, 1)",
                                backgroundColor: "rgba(75, 192, 192, 0.2)",
                                fill: true,
                                tension: 0.3,
                                pointRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { position: "top" }},
                            scales: {
                                x: { title: { display: true, text: "Month" }},
                                y: { title: { display: true, text: "Sales (Rs.)" }, beginAtZero: true }
                            }
                        }
                    });
                }
            })
            .catch(error => console.error("Error loading sales chart:", error));
    }

    function loadCashFlowChart() {
        fetch("getCashFlowChart.php")
            .then(response => response.json())
            .then(data => {
                if (cashFlowChart) {
                    cashFlowChart.data.labels = data.labels;
                    cashFlowChart.data.datasets[0].data = data.cashIn;
                    cashFlowChart.data.datasets[1].data = data.cashOut;
                    cashFlowChart.update();
                } else {
                    cashFlowChart = new Chart(document.getElementById("cashFlowChart"), {
                        type: "bar",
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: "Sales",
                                    data: data.cashIn,
                                    backgroundColor: "rgba(75, 192, 192, 0.6)",
                                    borderColor: "rgba(75, 192, 192, 1)",
                                    borderWidth: 1
                                },
                                {
                                    label: "Purchases",
                                    data: data.cashOut,
                                    backgroundColor: "#3caaaa",
                                    borderColor: "#3caaaa",
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { position: "top" }},
                            scales: {
                                x: { title: { display: true, text: "Month" }},
                                y: { beginAtZero: true, title: { display: true, text: "Amount (Rs.)" }}
                            }
                        }
                    });
                }
            })
            .catch(error => console.error("Error loading cash flow chart:", error));
    }

	function loadProfitChart() {
		fetch("getProfitChartData.php")
			.then(response => response.json())
			.then(data => {
				if (profitChart) {
					profitChart.data.labels = data.labels;
					profitChart.data.datasets[0].data = data.profit;
					profitChart.update();
				} else {
					profitChart = new Chart(document.getElementById("profitChart"), {
						type: "line",
						data: {
							labels: data.labels,
							datasets: [{
								label: "Profit (Rs.)",
								data: data.profit,
								borderColor: "rgb(128, 255, 78)",
								backgroundColor: "rgb(231, 255, 222)",
								fill: true,
								tension: 0.3,
								pointRadius: 4
							}]
						},
						options: {
							responsive: true,
							plugins: { legend: { position: "top" }},
							scales: {
								x: { title: { display: true, text: "Month" }},
								y: { beginAtZero: true, title: { display: true, text: "Profit (Rs.)" }}
							}
						}
					});
				}
			})
			.catch(error => console.error("Error loading profit chart:", error));
	}
	
    function loadRevenueVsProfitChart() {
        fetch("getRevenueVsProfitChart.php")
        .then(response => response.json())
        .then(data => {
        const profits = data.profit;
        const revenue = data.revenue;
    
        const profitData = profits.map(value => value > 0 ? value : 0);
        const lossData = profits.map(value => value < 0 ? Math.abs(value) : 0);
    
        revenueVsProfitChart = new Chart(document.getElementById("revenueVsProfitChart"), {
            type: "bar",
            data: {
            labels: data.labels,
            datasets: [
                {
                label: "Revenue",
                data: revenue,
                backgroundColor: "rgba(235, 208, 54, 0.6)", // Yellow
                borderColor: "rgb(235, 208, 54)",
                borderWidth: 1
                },
                {
                label: "Profit",
                data: profitData,
                backgroundColor: "rgba(0, 200, 83, 0.6)", // Green
                borderColor: "rgb(0, 200, 83)",
                borderWidth: 1
                },
                {
                label: "Loss",
                data: lossData,
                backgroundColor: "rgba(244, 67, 54, 0.6)", // Red
                borderColor: "rgb(244, 67, 54)",
                borderWidth: 1
                }
            ]
            },
            options: {
            responsive: true,
            plugins: {
                legend: { position: "top" }
            },
            scales: {
                x: { title: { display: true, text: "Month" }},
                y: { beginAtZero: true, title: { display: true, text: "Amount (Rs.)" }}
            }
            }
        });
        })
        .catch(error => console.error("Error loading revenue vs profit chart:", error));
    }
	
	

	function populateMonthSelector() {
		const selector = document.getElementById("monthSelector");
		const now = new Date();
		for (let i = 0; i < 12; i++) {
            const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
            const value = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
            const label = date.toLocaleString('default', { month: 'long', year: 'numeric' });

            const option = document.createElement("option");
            option.value = value;
            option.textContent = label;

            selector.appendChild(option);
        }
	}
	
	function loadExpenseChart(monthYear) {
        fetch("getExpenseBreakdown.php?month=" + monthYear)
            .then(response => response.json())
            .then(data => {
                if (expenseChart) {
                    expenseChart.data.labels = data.labels;
                    expenseChart.data.datasets[0].data = data.data;
                    expenseChart.update();
                } else {
                    expenseChart = new Chart(document.getElementById("expenseChart"), {
                        type: "pie",
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.data,
                                backgroundColor: [
                                    "rgba(255, 99, 132, 0.6)",
                                    "rgba(54, 162, 235, 0.6)",
                                    "rgba(255, 206, 86, 0.6)",
                                    "rgba(75, 192, 192, 0.6)",
                                    "rgba(153, 102, 255, 0.6)",
                                    "rgba(255, 159, 64, 0.6)"
                                ],
                                borderColor: "rgba(255, 255, 255, 1)",
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: "right"
                                }
                            }
                        }
                    });
                }
            })
            .catch(error => console.error("Error loading expense chart:", error));
    }
	
	// Setup dropdown
    populateMonthSelector();

    // Initial load for current month
    const selector = document.getElementById("monthSelector");
    loadExpenseChart(selector.value);

    // Reload on change
    selector.addEventListener("change", function () {
        loadExpenseChart(this.value);
    });
	

    // Initial chart load
    loadSalesChart();
    loadCashFlowChart();
	loadExpenseChart();
	loadProfitChart();
	loadRevenueVsProfitChart();

    // Auto-refresh every 30 seconds
    setInterval(() => {
        loadSalesChart();
        loadCashFlowChart();
		loadExpenseChart();
		loadProfitChart();
		loadRevenueVsProfitChart();
    }, 30000);



	const viewFilter = document.getElementById("viewFilter");

    function loadFinancialOverview() {
        const selectedFilter = viewFilter.value.toLowerCase(); // Get current filter (monthly, quarterly, yearly)

        fetch(`getFinancialOverview.php?filter=${selectedFilter}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("totalSales").innerText = `Rs. ${parseFloat(data.totalSales).toLocaleString()}`;
                document.getElementById("totalPurchases").innerText = `Rs. ${parseFloat(data.totalPurchases).toLocaleString()}`;
                document.getElementById("totalExpenses").innerText = `Rs. ${parseFloat(data.totalExpenses).toLocaleString()}`;
                document.getElementById("outstandingPayments").innerText = `Rs. ${parseFloat(data.outstandingPayment).toLocaleString()}`;
            })
            .catch(error => console.error("Error loading financial overview:", error));
    }

    // Initial load
    loadFinancialOverview();

    // Reload on filter change
    viewFilter.addEventListener("change", loadFinancialOverview);

    // Auto-refresh every 30 seconds
    setInterval(loadFinancialOverview, 30000);
});

document.addEventListener('DOMContentLoaded', function () {
    // Activate sidebar menu based on current path
    //updateActiveMenu();

    const profileIcon = document.getElementById("profile-icon");
    const profileMenu = document.querySelector(".profile");

    // Toggle dropdown visibility
    profileIcon.addEventListener("click", function (e) {
        e.stopPropagation(); // Prevent click from bubbling up
        profileMenu.classList.toggle("active");
    });

    // Close dropdown if clicking outside
    document.addEventListener("click", function (e) {
        if (!profileMenu.contains(e.target)) {
            profileMenu.classList.remove("active");
        }
    });
});