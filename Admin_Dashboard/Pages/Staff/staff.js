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


document.addEventListener("DOMContentLoaded", function () {
    // Helper function to set error messages
    function setError(inputId, errorMessage) {
      const errorElement = document.getElementById(`${inputId}-error`);
      errorElement.textContent = errorMessage; // Set error message
    }
  
    // Helper function to clear error messages
    function clearError(inputId) {
      const errorElement = document.getElementById(`${inputId}-error`);
      errorElement.textContent = ""; // Clear error message
    }
  
  // Validate origin (letters only)
  document.getElementById("name").addEventListener("input", function () {
    const value = this.value.trim();
    if (!/^[a-zA-Z\s]+$/.test(value)) {
      setError("name", "Name must contain only letters.");
    } else {
      clearError("name");
    }
  });

  // Password validation
document.getElementById("password").addEventListener("input", function () {
    const value = this.value.trim();
    const passwordRegex =
      /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  
    if (!passwordRegex.test(value)) {
      setError(
        "password",
        "Password must be at least 8 characters long, include at least one lowercase letter, one uppercase letter, one number, and one special character."
      );
    } else {
      clearError("password");
    }
  });
  
  // Contact number validation
  document.getElementById("contactNo").addEventListener("input", function () {
    const value = this.value.trim();
    const contactNoRegex = /^\d{10}$/;
  
    if (!contactNoRegex.test(value)) {
      setError("contactNo", "Contact number must be exactly 10 digits.");
    } else {
      clearError("contactNo");
    }
  });
  
  // Email validation
  document.getElementById("email").addEventListener("input", function () {
    const value = this.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  
    if (!emailRegex.test(value)) {
      setError("email", "Please enter a valid email address.");
    } else {
      clearError("email");
    }
  });


    document.getElementById("AddStaffForm").addEventListener("submit", function (e) {
        const password = document.getElementById("password").value.trim();
        const role = document.getElementById("role").value.trim();
        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const contactNo = document.getElementById("contactNo").value.trim();
      
        let isValid = true;
      
        // Validate name (only letters and spaces allowed)
        if (!/^[a-zA-Z\s]+$/.test(name)) {
          setError("name", "Name must contain only letters.");
          isValid = false;
        } else {
          clearError("name");
        }

        if (!/^[a-zA-Z\s]+$/.test(role)) {
            setError("name", "Name must contain only letters.");
            isValid = false;
          } else {
            clearError("role");
          }
      
        // Validate password (at least 8 characters, one lowercase, one uppercase, one number, one special character)
        const passwordRegex =
          /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/;
        if (!passwordRegex.test(password)) {
          setError(
            "password",
            "Password must be at least 8 characters long, include at least one lowercase letter, one uppercase letter, one number, and one special character."
          );
          isValid = false;
        } else {
          clearError("password");
        }
      
        // Validate email (basic email format)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
          setError("email", "Please enter a valid email address.");
          isValid = false;
        } else {
          clearError("email");
        }
      
        // Validate contact number (exactly 10 digits)
        const contactNoRegex = /^\d{10}$/;
        if (!contactNoRegex.test(contactNo)) {
          setError("contactNo", "Contact number must be exactly 10 digits.");
          isValid = false;
        } else {
          clearError("contactNo");
        }
      
        // If invalid, prevent submission
        if (!isValid) {
          e.preventDefault();
        }
      });
    });     
