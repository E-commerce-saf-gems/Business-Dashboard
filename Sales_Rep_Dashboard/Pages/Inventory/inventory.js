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
