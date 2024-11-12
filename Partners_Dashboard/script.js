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

// Sample data for monthly sales
const salesData = {
	labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
	datasets: [{
		label: "Sales ($)",
		data: [500, 700, 800, 600, 750, 900, 850, 950, 700, 800, 1000, 1100], // Sample data points
		borderColor: "rgba(75, 192, 192, 1)",
		backgroundColor: "rgba(75, 192, 192, 0.2)",
		fill: true,
		tension: 0.3, // Curve smoothness
		pointRadius: 4,
		pointBackgroundColor: "rgba(75, 192, 192, 1)"
	}]
};

// Configuration options for the chart
const config = {
	type: "line",
	data: salesData,
	options: {
		responsive: true,
		plugins: {
			legend: {
				display: true,
				position: "top"
			}
		},
		scales: {
			x: {
				title: {
					display: true,
					text: "Month"
				}
			},
			y: {
				title: {
					display: true,
					text: "Sales ($)"
				},
				beginAtZero: true
			}
		}
	}
};

// Render the chart in the canvas with id 'salesChart'
const salesChart = new Chart(
	document.getElementById("salesChart"),
	config
);


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

// cashflow
// Updated Data and configuration for the Cash Flow Bar Chart
const cashFlowData = {
	labels: Array.from({ length: 10 }, (_, i) => `${i + 1}`), // Labels from 1 to 15 representing days of the month
	datasets: [
		{
			label: 'Cash In',
			data: [120, 150, 200, 180, 210, 230, 170, 160, 200, 220], // Example data for Cash In each day
			backgroundColor: 'rgba(75, 192, 192, 0.6)', // Teal color
			borderColor: 'rgba(75, 192, 192, 1)',
			borderWidth: 1
		},
		{
			label: 'Cash Out',
			data: [100, 130, 150, 140, 170, 160, 150, 140, 180, 190], // Example data for Cash Out each day
			backgroundColor: "#3caaaa", // Red color
			borderColor: '#3caaaa',
			borderWidth: 1
		}
	]
};

const cashFlowConfig = {
	type: 'bar',
	data: cashFlowData,
	options: {
		scales: {
			y: {
				beginAtZero: true,
				title: {
					display: true,
					text: 'Amount ($)'
				}
			},
			x: {
				title: {
					display: true,
					text: 'Day'
				},
				stacked: false // Keeps bars side-by-side for each day
			}
		},
		plugins: {
			legend: {
				position: 'top'
			}
		}
	}
};

// Initialize the Cash Flow Bar Chart
const cashFlowChart = new Chart(
	document.getElementById('cashFlowChart'),
	cashFlowConfig
);

// cashflow

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
