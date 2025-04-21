document.addEventListener("DOMContentLoaded", function () {
    const profileIcon = document.getElementById("profile-icon");
    const profileMenu = document.querySelector(".profile");

    profileIcon.addEventListener("click", function () {
        profileMenu.classList.toggle("active"); // Toggle the 'active' class
    });

    // Close the dropdown if clicked outside
    document.addEventListener("click", function (event) {
        if (!profileMenu.contains(event.target) && event.target !== profileIcon) {
            profileMenu.classList.remove("active");
        }
    });
});

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
const monthlyuserData = {
	labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
	datasets: [{
		label: "Users",
		data: [100, 300, 400, 600, 650, 900, 850, 950, 700, 800, 1000, 1100], // Sample data points
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
	data: monthlyuserData,
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
					text: "Users"
				},
				beginAtZero: true
			}
		}
	}
};

// Render the chart in the canvas with id 'salesChart'
const monthlyuserChart = new Chart(
	document.getElementById("VisitChart"),
	config
);

document.addEventListener("DOMContentLoaded", function () {
    const searchButton = document.getElementById("searchButton");
    const searchInput = document.getElementById("searchInput");
    const searchResults = document.getElementById("searchResults");

    // Handle search button click
    searchButton.addEventListener("click", function () {
        const query = searchInput.value.trim().toLowerCase(); // Get the search query
        searchResults.innerHTML = ""; // Clear previous results

        if (query === "") {
            searchResults.innerHTML = "<p>Please enter a search term.</p>";
            return;
        }

        // Get all text content from the webpage
        const bodyText = document.body.innerText.toLowerCase();

        // Check if the query exists in the webpage text
        if (bodyText.includes(query)) {
            searchResults.innerHTML = `<p>Found: "<span class="highlight">${query}</span>"</p>`;
            highlightText(query); // Highlight the matching text
        } else {
            searchResults.innerHTML = `<p>No results found for "<span class="highlight">${query}</span>".</p>`;
        }
    });

    // Function to highlight matching text on the webpage
    function highlightText(query) {
        const elements = document.querySelectorAll("body *:not(script):not(style)");

        elements.forEach(element => {
            if (element.children.length === 0 && element.innerText) {
                const regex = new RegExp(`(${query})`, "gi");
                element.innerHTML = element.innerHTML.replace(regex, `<span class="highlight">$1</span>`);
            }
        });
    }
});


// Sample data for users
document.addEventListener("DOMContentLoaded", function () {
    // Fetch gender-wise data from the backend
    fetch('getGenderData.php')
        .then(response => response.json())
        .then(data => {
            // Check if the API returned an error
            if (data.error) {
                console.error('Error from backend:', data.error);
                return;
            }

            // Extract labels (genders) and data (counts) from the response
            const labels = data.map(item => item.gender); // e.g., ["Male", "Female", "Other"]
            const counts = data.map(item => item.count); // e.g., [50, 30, 5]

            // Chart data
            const genderData = {
                labels: labels,
                datasets: [{
                    data: counts,
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.6)", // Male color
                        "rgba(75, 192, 192, 0.6)", // Female color
                        "rgba(153, 102, 255, 0.6)"  // Other color
                    ],
                    borderColor: [
                        "rgba(255, 99, 132, 1)",
                        "rgba(75, 192, 192, 1)",
                        "rgba(153, 102, 255, 1)"
                    ],
                    borderWidth: 1
                }]
            };

            // Chart configuration
            const userConfig = {
                type: "pie",
                data: genderData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: "right"
                        },
                        datalabels: {
                            color: "#000", // Text color
                            font: {
                                size: 14, // Font size
                                weight: "bold"
                            },
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1); // Calculate percentage
                                return `${value} (${percentage}%)`; // Display count and percentage
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels] // Enable the Datalabels plugin
            };

            // Render the chart in the canvas with id 'userChart'
            new Chart(document.getElementById("userChart"), userConfig);
        })
        .catch(error => console.error('Error fetching gender data:', error));

        fetch('getStaffData.php')
        .then(response => response.json())
        .then(data => {
            // Extract labels (roles) and data (counts) from the response
            const labels = data.map(item => item.role); // e.g., ["Partners", "Sales Res.", "Accountants", "Admin"]
            const counts = data.map(item => item.count); // e.g., [3, 2, 1, 1]

            // Update the chart data
            const staffData = {
                labels: labels,
                datasets: [{
                    data: counts,
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.6)", // Partners color
                        "rgba(75, 192, 192, 0.6)", // Sales Res color
                        "rgba(54, 162, 235, 0.6)", // Accountant color
                        "rgba(153, 102, 255, 0.6)"  // Admin color
                    ],
                    borderColor: [
                        "rgba(255, 99, 132, 1)",
                        "rgba(75, 192, 192, 1)",
                        "rgba(54, 162, 235, 1)",
                        "rgba(153, 102, 255, 1)"
                    ],
                    borderWidth: 1
                }]
            };

            // Configuration for the chart
            const staffConfig = {
                type: "pie",
                data: staffData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: "right"
                        },
                        datalabels: {
                            color: "#000", // Text color
                            font: {
                                size: 14, // Font size
                                weight: "bold"
                            },
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1); // Calculate percentage
                                return `${percentage}%`; // Display percentage
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels] // Enable the Datalabels plugin
            };

            // Render the chart
            new Chart(document.getElementById("staffFlowChart"), staffConfig);
        })
        .catch(error => console.error('Error fetching staff data:', error));
});
// Render the pie chart in the canvas with id 'gemChart'
const userChart = new Chart(
	document.getElementById("userChart"),
	userConfig
);

document.addEventListener("DOMContentLoaded", function () {
    // Fetch staff data from the backend
    fetch('getStaffData.php')
        .then(response => response.json())
        .then(data => {
            // Extract labels (roles) and data (counts) from the response
            const labels = data.map(item => item.role); // e.g., ["Partners", "Sales Res.", "Accountants", "Admin"]
            const counts = data.map(item => item.count); // e.g., [3, 2, 1, 1]

            // Update the chart data
            const staffData = {
                labels: labels,
                datasets: [{
                    data: counts,
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.6)", // Partners color
                        "rgba(75, 192, 192, 0.6)", // Sales Res color
                        "rgba(54, 162, 235, 0.6)", // Accountant color
                        "rgba(153, 102, 255, 0.6)"  // Admin color
                    ],
                    borderColor: [
                        "rgba(255, 99, 132, 1)",
                        "rgba(75, 192, 192, 1)",
                        "rgba(54, 162, 235, 1)",
                        "rgba(153, 102, 255, 1)"
                    ],
                    borderWidth: 1
                }]
            };

            // Configuration for the chart
            const staffConfig = {
                type: "pie",
                data: staffData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: "right"
                        },
                        datalabels: {
                            color: "#000", // Text color
                            font: {
                                size: 14, // Font size
                                weight: "bold"
                            },
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1); // Calculate percentage
                                return `${percentage}%`; // Display percentage
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels] // Enable the Datalabels plugin
            };

            // Render the chart
            new Chart(document.getElementById("staffFlowChart"), staffConfig);
        })
        .catch(error => console.error('Error fetching staff data:', error));
});

// Render the pie chart in the canvas with id 'staffFlowChart'
const staffChart = new Chart(
    document.getElementById("staffFlowChart"),
    staffConfig
);

