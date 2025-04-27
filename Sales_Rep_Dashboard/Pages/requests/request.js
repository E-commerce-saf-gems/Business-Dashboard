document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            
            const requestId = this.getAttribute('data-id');
            
            const isConfirmed = confirm("Are you sure you want to delete this request?");
            
            if (isConfirmed) {
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


