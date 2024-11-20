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

      // Real-time validation
      document.addEventListener("DOMContentLoaded", function () {
        const dateInput = document.getElementById("date");
        const timeInput = document.getElementById("time");
        const dateError = document.getElementById("dateError");
        const timeError = document.getElementById("timeError");
        const form = document.getElementById("editSalesForm");

        function validateDate() {
          const today = new Date();
          const selectedDate = new Date(dateInput.value);

          // Reset errors and check date validity
          dateError.style.display = "none";
          if (selectedDate < today.setHours(0, 0, 0, 0)) {
            dateError.style.display = "block";
            return false;
          }
          return true;
        }

        function validateTime() {
          const now = new Date();
          const selectedDate = new Date(dateInput.value);
          const selectedTime = new Date(
            `${dateInput.value}T${timeInput.value}`
          );

          // Reset errors and check time validity
          timeError.style.display = "none";
          if (selectedDate.toDateString() === now.toDateString()) {
            if (selectedTime <= now) {
              timeError.style.display = "block";
              return false;
            }
          }
          return true;
        }

        // Event listeners for real-time validation
        dateInput.addEventListener("input", validateDate);
        timeInput.addEventListener("input", validateTime);

        // Validate before form submission
        form.addEventListener("submit", function (e) {
          const isDateValid = validateDate();
          const isTimeValid = validateTime();
          if (!isDateValid || !isTimeValid) {
            e.preventDefault(); // Prevent form submission
          }
        });
      });




