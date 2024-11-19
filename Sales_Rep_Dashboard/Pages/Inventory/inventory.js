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

  // Validate weight (greater than 0)
  document.getElementById("weight").addEventListener("input", function () {
    const value = parseFloat(this.value);
    if (isNaN(value) || value <= 0) {
      setError("weight", "Weight must be greater than 0.");
    } else {
      clearError("weight");
    }
  });

  // Validate amount (greater than 0)
  document.getElementById("amount").addEventListener("input", function () {
    const value = parseFloat(this.value);
    if (isNaN(value) || value <= 0) {
      setError("amount", "Amount must be greater than 0.");
    } else {
      clearError("amount");
    }
  });

  // Validate amountSettled (greater than 0, less than or equal to amount)
  document.getElementById("amountSettled").addEventListener("input", function () {
    const settledValue = parseFloat(this.value);
    const amountValue = parseFloat(document.getElementById("amount").value);
    if (isNaN(settledValue) || settledValue <= 0) {
      setError("amountSettled", "Amount Settled must be greater than 0.");
    } else if (settledValue > amountValue) {
      setError("amountSettled", "Amount Settled cannot exceed Amount.");
    } else {
      clearError("amountSettled");
    }
  });

  // Validate shape (letters only)
  document.getElementById("shape").addEventListener("input", function () {
    const value = this.value.trim();
    if (!/^[a-zA-Z\s]+$/.test(value)) {
      setError("shape", "Shape must contain only letters.");
    } else {
      clearError("shape");
    }
  });

  // Validate colour (letters only)
  document.getElementById("colour").addEventListener("input", function () {
    const value = this.value.trim();
    if (!/^[a-zA-Z\s]+$/.test(value)) {
      setError("colour", "Colour must contain only letters.");
    } else {
      clearError("colour");
    }
  });

  // Validate origin (letters only)
  document.getElementById("origin").addEventListener("input", function () {
    const value = this.value.trim();
    if (!/^[a-zA-Z\s]+$/.test(value)) {
      setError("origin", "Origin must contain only letters.");
    } else {
      clearError("origin");
    }
  });

  // Final form validation on submit
  document.getElementById("addgemForm").addEventListener("submit", function (e) {
    const size = parseFloat(document.getElementById("size").value);
    const weight = parseFloat(document.getElementById("weight").value);
    const amount = parseFloat(document.getElementById("amount").value);
    const amountSettled = parseFloat(document.getElementById("amountSettled").value);
    const shape = document.getElementById("shape").value.trim();
    const colour = document.getElementById("colour").value.trim();
    const origin = document.getElementById("origin").value.trim();

    let isValid = true;

    // Re-run all validations
    if (isNaN(weight) || weight <= 0) {
      setError("weight", "Weight must be greater than 0.");
      isValid = false;
    }
    if (isNaN(amount) || amount <= 0) {
      setError("amount", "Amount must be greater than 0.");
      isValid = false;
    }
    if (isNaN(amountSettled) || amountSettled <= 0 || amountSettled > amount) {
      setError("amountSettled", "Amount Settled must be greater than 0 and not exceed Amount.");
      isValid = false;
    }
    if (!/^[a-zA-Z\s]+$/.test(shape)) {
      setError("shape", "Shape must contain only letters.");
      isValid = false;
    }
    if (!/^[a-zA-Z\s]+$/.test(colour)) {
      setError("colour", "Colour must contain only letters.");
      isValid = false;
    }
    if (!/^[a-zA-Z\s]+$/.test(origin)) {
      setError("origin", "Origin must contain only letters.");
      isValid = false;
    }

    // If invalid, prevent submission
    if (!isValid) {
      e.preventDefault();
    }
  });
});



document.getElementById("editgemForm").addEventListener("submit", function (e) {
  // Form fields for validation
  const size = document.getElementById("size").value;
  const shape = document.getElementById("shape").value;
  const color = document.getElementById("colour").value;
  const type = document.getElementById("type").value;
  const weight = document.getElementById("weight").value;
  const origin = document.getElementById("origin").value;
  const amount = document.getElementById("amount").value;
  const image = document.getElementById("image").value;
  const certificate = document.getElementById("certificate").value;
  const description = document.getElementById("description").value;
  const visibility = document.getElementById("visibility").value;
  const availability = document.getElementById("availability").value;
  const buyer_id = document.getElementById("buyer_id").value;

  // Array of required fields
  const requiredFields = [
    { field: size, name: "Size" },
    { field: shape, name: "Shape" },
    { field: color, name: "Colour" },
    { field: type, name: "Type" },
    { field: weight, name: "Weight" },
    { field: origin, name: "Origin" },
    { field: amount, name: "Amount" },
    { field: image, name: "Image" },
    { field: certificate, name: "Certificate" },
    { field: description, name: "Description" },
    { field: visibility, name: "Visibility" },
    { field: availability, name: "Availability" },
    { field: buyer_id, name: "Buyer ID" }
  ];

  // Check if any required field is empty
  const emptyFields = requiredFields.filter(item => item.field === "");

  if (emptyFields.length > 0) {
    // Prevent form submission and alert if there are empty fields
    e.preventDefault();
    alert("Please fill in all required fields: " + emptyFields.map(item => item.name).join(", "));
  }
});


document.addEventListener('DOMContentLoaded', () => {
  const buyerDropdown = document.getElementById('buyer');

  fetch('./getBuyer.php')
      .then(response => response.json())
      .then(buyers => {
          buyers.forEach(buyer => {
              const option = document.createElement('option');
              option.value = buyer.buyer_id;
              option.textContent = buyer.email;
              buyerDropdown.appendChild(option);
          });
      })
      .catch(error => {
          console.error('Error fetching buyers:', error);
      });
});

