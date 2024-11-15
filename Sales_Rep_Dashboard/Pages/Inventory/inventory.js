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



document.getElementById("addgemForm").addEventListener("submit", function (e) {
    // Prevent form submission for validation
    e.preventDefault();
  
    // Form fields for validation
    const date = document.getElementById("date").value;
    const size = document.getElementById("color").value;
    const shape = document.getElementById("shape").value;
    const color = document.getElementById("color").value;
    const type = document.getElementById("type").value;
    const weight = document.getElementById("weight").value;
    const origin = document.getElementById("origin").value;
    const amount = document.getElementById("amount").value;
    const image = document.getElementById("image").value;
    const certificate = document.getElementById("certificate").value;
    const description = document.getElementById("description").value;
    const buyer_id = document.getElementById("buyer_id").value;
  
    // Array of required fields
    const requiredFields = [
      { field: date, name: "Date" },
      { field: size, name: "Size" },
      { field: shape, name: "Shape" },
      { field: color, name: "Color" },
      { field: type, name: "Type" },
      { field: weight, name: "Weight" },
      { field: origin, name: "Origin" },
      { field: amount, name: "Amount" },
      { field: image, name: "Image" },
      { field: certificate, name: "Certificate" },
      { field: description, name: "Description" },
      { field: buyer_id, name: "Buyer ID" }
    ];
  
    // Check if any required field is empty
    const emptyFields = requiredFields.filter(item => item.field === "");
  
    if (emptyFields.length > 0) {
      // Alert if there are empty fields
      alert("Please fill in all required fields: " + emptyFields.map(item => item.name).join(", "));
    } else {
      // Submit the form if all fields are filled
      alert("Form filled correctly! Redirecting to the inventory page.");
      // Redirect to request.html only if validation is successful
      window.location.href = "../../../Sales_Rep_Dashboard/Pages/Inventory/inventory.php";
    }
  });
  