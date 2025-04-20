// JavaScript to handle deletion and confirmation
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link action
            
            // Get the ID of the request from the data-id attribute
            const requestId = this.getAttribute('data-id');
            
            // Confirm deletion
            const isConfirmed = confirm("Are you sure you want to delete this request?");
            
            if (isConfirmed) {
                // Send an AJAX request to delete the record from the database
                fetch('./deleteRequest.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `request_id=${requestId}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        // Remove the row from the table if deletion is successful
                        const row = button.closest('tr');
                        row.remove();
                        alert("Request deleted successfully!");
                    } else {
                        alert("There was an error deleting the request.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("An error occurred while deleting the request.");
                });
            }
        });
    });
});
