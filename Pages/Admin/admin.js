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

/*add customer validation*/
document.getElementById("AddCustomerForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission

    // Get form values
    const date = document.getElementById("date").value;
    const customerName = document.getElementById("customer").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const nic = document.getElementById("nic").value.trim();
    const email = document.getElementById("email").value.trim();
    const totalPurchases = document.getElementById("totalPurchases").value.trim();

    // Validation flags and error message
    let isValid = true;
    let errorMessage = "";

    // Validate each field
    if (!date) {
        isValid = false;
        errorMessage += "Please enter a valid date.\n";
    }

    if (customerName === "" || isNaN(customerName)) {
        isValid = false;
        errorMessage += "Please enter a valid customer name.\n";
    }

    if (phone === "" || !/^\d{10}$/.test(phone)) {
        isValid = false;
        errorMessage += "Please enter a valid 10-digit phone number.\n";
    }

    if (nic === "" || !/^[A-Z0-9]{9,12}$/.test(nic)) {
        isValid = false;
        errorMessage += "Please enter a valid NIC number.\n";
    }

    if (email === "" || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        isValid = false;
        errorMessage += "Please enter a valid email address.\n";
    }

    const totalPurchasesValue = parseFloat(totalPurchases);
    if (isNaN(totalPurchasesValue) || totalPurchasesValue < 0) {
        isValid = false;
        errorMessage += "Please enter a valid total purchases amount.\n";
    }

    // Redirect if valid; otherwise, show error message
    if (isValid) {
        window.location.href = "../../Pages/Admin/customers.html";
    } else {
        alert(errorMessage); // Show error message
    }
});

/*Add Staff form validation*/
document.getElementById("AddStaffForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission

    // Get form values
    const date = document.getElementById("date").value;
    const staffId = document.getElementById("staffid").value.trim();
    const status = document.getElementById("status").value;
    const phoneNumber = document.getElementById("staff").value.trim();

    // Initialize validation flag and error message
    let isValid = true;
    let errorMessage = "";

    // Validate Date
    if (!date) {
        isValid = false;
        errorMessage += "Please enter a valid date.\n";
    }

    // Validate Staff ID
    if (!staffId || isNaN(staffId) || parseInt(staffId) <= 0) {
        isValid = false;
        errorMessage += "Please enter a valid Staff ID (positive number).\n";
    }

    // Validate Status
    if (!status) {
        isValid = false;
        errorMessage += "Please select a status.\n";
    }

    // Validate Phone Number (10-digit format)
    if (!phoneNumber || !/^\d{10}$/.test(phoneNumber)) {
        isValid = false;
        errorMessage += "Please enter a valid 10-digit phone number.\n";
    }

    // If valid, redirect; otherwise, show errors
    if (isValid) {
        window.location.href = "../../Pages/Admin/Staff.html";
    } else {
        alert(errorMessage); // Display validation errors
    }
});
