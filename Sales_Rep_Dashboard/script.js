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


const salesData = {
	labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
	datasets: [{
		label: "Sales ($)",
		data: [500, 700, 800, 600, 750, 900, 850, 950, 700, 800, 1000, 1100], 
		borderColor: "rgba(75, 192, 192, 1)",
		backgroundColor: "rgba(75, 192, 192, 0.2)",
		fill: true,
		tension: 0.3, 
		pointRadius: 4,
		pointBackgroundColor: "rgba(75, 192, 192, 1)"
	}]
};


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


const salesChart = new Chart(
	document.getElementById("salesChart"),
	config
);



const gemData = {
	labels: ["Ruby", "Emerald", "Sapphire", "Amethyst", "Diamond"],
	datasets: [{
		data: [12, 19, 7, 10, 15], 
		backgroundColor: [
			"rgba(255, 99, 132, 0.6)", 
			"rgba(75, 192, 192, 0.6)", 
			"rgba(54, 162, 235, 0.6)", 
			"rgba(153, 102, 255, 0.6)", 
			"rgba(255, 206, 86, 0.6)"   
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


const gemConfig = {
	type: "pie",
	data: gemData,
	options: {
		responsive: true,
		plugins: {
			legend: {
				display: true,
				position: "right" 
			}
		}
	}
};


const gemChart = new Chart(
	document.getElementById("gemChart"),
	gemConfig
);


const cashFlowData = {
	labels: Array.from({ length: 10 }, (_, i) => `${i + 1}`), 
	datasets: [
		{
			label: 'Cash In',
			data: [120, 150, 200, 180, 210, 230, 170, 160, 200, 220], 
			backgroundColor: 'rgba(75, 192, 192, 0.6)', 
			borderColor: 'rgba(75, 192, 192, 1)',
			borderWidth: 1
		},
		{
			label: 'Cash Out',
			data: [100, 130, 150, 140, 170, 160, 150, 140, 180, 190], 
			backgroundColor: "#3caaaa", 
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
				stacked: false 
			}
		},
		plugins: {
			legend: {
				position: 'top'
			}
		}
	}
};


const cashFlowChart = new Chart(
	document.getElementById('cashFlowChart'),
	cashFlowConfig
);

