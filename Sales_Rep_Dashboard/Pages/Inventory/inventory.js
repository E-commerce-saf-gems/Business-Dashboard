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

document.getElementById("addgemForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent page reload

    // Show the success message
    const successMessage = document.getElementById("successMessage");
    successMessage.classList.add("show");

    // Optionally hide the success message after 3 seconds
    setTimeout(() => {
        successMessage.classList.remove("show");
    }, 3000);

    // Reset the form fields after submission
    event.target.reset();
});

