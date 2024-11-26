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



  
  

