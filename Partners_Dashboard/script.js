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



// Sample data for gemstone types
const gemData = {
	labels: ["Ruby", "Emerald", "Sapphire", "Amethyst", "Diamond"],
	datasets: [{
		data: [12, 19, 7, 10, 15], // Sample quantities for each gemstone type
		backgroundColor: [
			"rgba(255, 99, 132, 0.6)", // Ruby color
			"rgba(75, 192, 192, 0.6)", // Emerald color
			"rgba(54, 162, 235, 0.6)", // Sapphire color
			"rgba(153, 102, 255, 0.6)", // Amethyst color
			"rgba(255, 206, 86, 0.6)"   // Diamond color
		],
		borderColor: [
			"rgba(255, 99, 132, 1)",
			"rgba(75, 192, 192, 1)",
			"rgba(54, 162, 235, 1)",
			"rgba(153, 102, 255, 1)",
			"rgba(255, 206, 86, 1)"
		],
		borderWidth: 1
	}]
};

// Configuration for the gemstone types pie chart
const gemConfig = {
	type: "pie",
	data: gemData,
	options: {
		responsive: true,
		plugins: {
			legend: {
				display: true,
				position: "right" // Position legend on the right
			}
		}
	}
};

// Render the pie chart in the canvas with id 'gemChart'
const gemChart = new Chart(
	document.getElementById("gemChart"),
	gemConfig
);

document.addEventListener("DOMContentLoaded", function () {
    let salesChart;
    let cashFlowChart;
	

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
                                    label: "Cash In",
                                    data: data.cashIn,
                                    backgroundColor: "rgba(75, 192, 192, 0.6)",
                                    borderColor: "rgba(75, 192, 192, 1)",
                                    borderWidth: 1
                                },
                                {
                                    label: "Cash Out",
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



	

    // Initial chart load
    loadSalesChart();
    loadCashFlowChart();
	

    // Auto-refresh every 30 seconds
    setInterval(() => {
        loadSalesChart();
        loadCashFlowChart();
		
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
    updateActiveMenu();

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


document.addEventListener('DOMContentLoaded', function() {
    // Define variables with example values if necessary
    let auctionRevenue = 1000;
    let totalRevenue = 3000;

    // Auction Revenue Chart
    new Chart(document.getElementById('auction-revenue-chart'), {
        type: 'pie',
        data: {
            labels: ['Auction Revenue', 'Other Revenue'],
            datasets: [{
                data: [auctionRevenue, totalRevenue - auctionRevenue],
                backgroundColor: ['rgba(75, 192, 192, 1)', 'rgba(75, 192, 192, 0.2)']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Average Bid Price Chart
    const avgBidData = {
        labels: ['Jul', 'Aug', 'Sep','Oct'],
        data: [900, 1200, 800,1000]
    };

    const avgBidCtx = document.getElementById('average-bid-chart').getContext('2d');
    new Chart(avgBidCtx, {
        type: 'line',
        data: {
            labels: avgBidData.labels,
            datasets: [{
                label: 'Average Bid Price',
                data: avgBidData.data,
				borderColor: "rgba(75, 192, 192, 1)",
				backgroundColor: "rgba(75, 192, 192, 0.2)",
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false,
                    title: { display: true, text: 'Average Bid Price ($)' }
                },
                x: { title: { display: true, text: 'Months' } }
            },
            plugins: { legend: { display: false } }
        }
    });
});