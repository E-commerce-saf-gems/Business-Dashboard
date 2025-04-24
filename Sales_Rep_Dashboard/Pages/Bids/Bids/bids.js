// Function to handle delete confirmation
document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".delete-btn");
    
    deleteButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const confirmed = confirm("Are you sure you want to delete this item?");
            
            if (confirmed) {
                // Here you would add code to delete the item from the database
                // For now, just remove the row from the table
                const row = button.closest("tr");
                row.remove();
            }
        });
    });
});

/* *************** */

document.addEventListener('DOMContentLoaded', function () {
  fetch('./getAvailableStones.php')
      .then(response => {
          console.log('Response Status:', response.status); // Should be 200
          return response.json();
      })
      .then(data => {
          console.log('Fetched Data:', data); // Inspect the received data
          const dropdown = document.getElementById('stone');
          if (data.length > 0) {
              data.forEach(stone => {
                  const option = document.createElement('option');
                  option.value = stone.stone_id;
                  option.textContent = `${stone.colour} - ${stone.shape} ${stone.type}, (Carats: ${stone.weight}) (Sales Amount: Rs.${stone.amount})`;
                  dropdown.appendChild(option);
              });
          } else {
              const option = document.createElement('option');
              option.value = '';
              option.textContent = 'No stones available';
              dropdown.appendChild(option);
          }
      })
      .catch(error => console.error('Error fetching stones:', error));
});








// Auto-hide success messages after 5 seconds
setTimeout(function() {
  const message = document.querySelector(".success-message");
  if (message) {
      message.style.display = "none";
  }
}, 5000);

//*******FILTER********
function filterTransactions() {
  const date = document.getElementById('date-filter').value;
  const customer = document.getElementById('customer-filter').value;

  // Prepare the URL with filter parameters
  let url = 'transactions.php?';
  if (date) url += `date=${encodeURIComponent(date)}&`;
  if (customer) url += `customer=${encodeURIComponent(customer)}&`;

  // Fetch filtered data from server(send the request to the server)
  fetch(url)
      .then(response => response.text())
      .then(data => {
          // Update the table body with filtered data
          document.getElementById('transaction-body').innerHTML = data;
      })
      .catch(error => console.error('Error:', error));
}



// Chart data
const chartsData = [
    { id: "chart1", data: [10, 20, 30], labels: ["Red", "Blue", "Green"], title: "Bidding Gemstone Colors Overview" },
    { id: "chart2", data: [15, 25, 35], labels: ["Round", "Square", "Oval"], title: "Bidding Gemstone Shapes Overview" },
    { id: "chart3", data: [40, 30, 20], labels: ["Ruby", "Emerald", "Sapphire"], title: "Bidding Gemstone Types Overview" },
    { id: "chart4", data: [50, 25], labels: ["Sri Lanka", "Madagascar"], title: "Bidding Gemstones from Around the World"}
];

// Function to create and display the chart
const createChart = (canvasId, data, labels, title) => {
  new Chart(document.getElementById(canvasId).getContext("2d"), {
    type: "pie",
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: ["teal", "yellow", "orange"]
      }]
    },
    options: {
      plugins: {
        title: {
          display: false, // Disable title from Chart.js since we handle it separately
        }
      }
    }
    });
};

// Function to update the slider and chart title visibility
const updateSlider = () => {
  charts.forEach((chart, index) => {
    chart.style.display = index === currentChartIndex ? "block" : "none";
    // Update the title visibility corresponding to the chart
    document.getElementById(`title${index + 1}`).style.display = index === currentChartIndex ? "block" : "none";
  });
};

// Initialize all charts
chartsData.forEach(chart => createChart(chart.id, chart.data, chart.labels, chart.title));

// Slider logic for navigation
let currentChartIndex = 0;
const charts = document.querySelectorAll(".chart-item"); // All chart canvases
const prevButton = document.getElementById("prev");
const nextButton = document.getElementById("next");

// Add event listeners for navigation buttons
prevButton.addEventListener("click", () => {
  if (currentChartIndex > 0) {
    currentChartIndex--; // Move to the previous chart
    updateSlider();
  }
});

nextButton.addEventListener("click", () => {
  if (currentChartIndex < charts.length - 1) {
    currentChartIndex++; // Move to the next chart
    updateSlider();
  }
});

// Initialize the slider and title visibility
updateSlider();




/* Function to fetch chart data from the API (PHP backend)
const fetchChartData = async () => {
    try {
      const response = await fetch('get_chart_data.php'); // URL to your PHP endpoint
      const data = await response.json();
  
      // Once the data is fetched, initialize the charts with dynamic data
      initializeCharts(data);
    } catch (error) {
      console.error('Error fetching chart data:', error);
    }
  };
  
  // Function to initialize the charts
  const initializeCharts = (chartData) => {
    // Prepare the data for the charts
    const colorData = Object.values(chartData.color);
    const colorLabels = Object.keys(chartData.color);
  
    const shapeData = Object.values(chartData.shape);
    const shapeLabels = Object.keys(chartData.shape);
  
    const typeData = Object.values(chartData.type);
    const typeLabels = Object.keys(chartData.type);
  
    const originData = Object.values(chartData.origin);
    const originLabels = Object.keys(chartData.origin);
  
    // Create each chart dynamically using the fetched data
    createChart('chart1', colorData, colorLabels, 'Bidding Gemstone Colors Overview');
    createChart('chart2', shapeData, shapeLabels, 'Bidding Gemstone Shapes Overview');
    createChart('chart3', typeData, typeLabels, 'Bidding Gemstone Types Overview');
    createChart('chart4', originData, originLabels, 'Bidding Gemstones from Around the World');
  };
  
  // Function to create and display the chart
  const createChart = (canvasId, data, labels, title) => {
    new Chart(document.getElementById(canvasId).getContext("2d"), {
      type: "pie",
      data: {
        labels: labels,
        datasets: [{
          data: data,
          backgroundColor: ["teal", "yellow", "orange", "red", "blue", "green"] // Adjust color array if needed
        }]
      },
      options: {
        plugins: {
          title: {
            display: false, // Disable title from Chart.js since we handle it separately
          }
        }
      }
    });
  };
  
  // Fetch chart data and initialize the charts
  fetchChartData();*/
  







