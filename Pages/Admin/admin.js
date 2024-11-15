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

    // Retrieve field values
    const nicNumber = document.getElementById("NIC").value.trim();
    const title = document.getElementById("title").value.trim();
    const firstName = document.getElementById("firstName").value.trim();
    const lastName = document.getElementById("lastName").value.trim();
    const phoneNumber = document.getElementById("phone").value.trim();
    const email = document.getElementById("email").value.trim();
    const status = document.getElementById("status").value.trim();
    const address1 = document.getElementById("address1").value.trim();
    const address2 = document.getElementById("address2").value.trim();
    const city = document.getElementById("city").value.trim();
    const country = document.getElementById("country").value.trim();
    const postalCode = document.getElementById("postalCode").value.trim();
    const dob = document.getElementById("DOB").value.trim();
    const token = document.getElementById("token").value.trim();
    const verificationStatus = document.getElementById("verificationStatus").value.trim();

    // Validation checks
    let isValid = true;
    let errorMessage = "";

    // NIC validation (alphanumeric and non-empty)
    if (!nicNumber || !/^[A-Za-z0-9]+$/.test(nicNumber)) {
        isValid = false;
        errorMessage += "Please enter a valid NIC number.\n";
    }

    // Name validation (non-empty)
    if (!firstName) {
        isValid = false;
        errorMessage += "Please enter the first name.\n";
    }
    if (!lastName) {
        isValid = false;
        errorMessage += "Please enter the last name.\n";
    }

    // Phone Number validation (10 digits)
    if (!/^\d{10}$/.test(phoneNumber)) {
        isValid = false;
        errorMessage += "Please enter a valid 10-digit phone number.\n";
    }

    // Email validation
    if (!/\S+@\S+\.\S+/.test(email)) {
        isValid = false;
        errorMessage += "Please enter a valid email address.\n";
    }

    // Address validation
    if (!address1) {
        isValid = false;
        errorMessage += "Address Line 1 is required.\n";
    }
    if (!city) {
        isValid = false;
        errorMessage += "City is required.\n";
    }
    if (!country) {
        isValid = false;
        errorMessage += "Country is required.\n";
    }

    // Date of Birth validation
    if (!dob) {
        isValid = false;
        errorMessage += "Date of Birth is required.\n";
    }

    // Token validation
    if (!token) {
        isValid = false;
        errorMessage += "Token is required.\n";
    }

    // If form is valid, simulate the link click
    if (isValid) {
        window.location.href = "../../Pages/Admin/customers.php"; // Redirect to the page
    } else {
        // If there are validation errors, display them
        alert(errorMessage);
    }
});


/*Add Staff form validation*/
document.getElementById("AddStaffForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the form from submitting automatically

    // Retrieve input values
    const date = document.getElementById("date").value;
    const staffId = document.getElementById("staffid").value.trim();
    const status = document.getElementById("status").value;
    const phoneNumber = document.getElementById("staff").value.trim();

    // Initialize a flag for form validity and a message for errors
    let isValid = true;
    let errorMessage = "";

    // Validate the Date field
    if (!date) {
        isValid = false;
        errorMessage += "Please select a date.\n";
    }

    // Validate the Staff ID - should be a positive number
    if (!staffId || isNaN(staffId) || parseInt(staffId) <= 0) {
        isValid = false;
        errorMessage += "Please enter a valid Staff ID (positive number).\n";
    }

    // Validate the Status selection
    if (!status) {
        isValid = false;
        errorMessage += "Please select a status.\n";
    }

    // Validate the Phone Number - should be a 10-digit number
    if (!phoneNumber || !/^\d{10}$/.test(phoneNumber)) {
        isValid = false;
        errorMessage += "Please enter a valid 10-digit phone number.\n";
    }

    // If all fields are valid, redirect to Staff.html
    if (isValid) {
        window.location.href = "../../Pages/Admin/Staff.html";
    } else {
        // Show error messages if validation failed
        alert(errorMessage);
    }
});

/*edit customer validation */
// Select the form element
document.getElementById("editCustomerForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the form from submitting immediately

    // Retrieve values of the fields
    const date = document.getElementById("date").value;
    const customerName = document.getElementById("customer").value.trim();
    const phoneNumber = document.getElementById("phone").value.trim();
    const nicNumber = document.getElementById("nic").value.trim();
    const email = document.getElementById("email").value.trim();
    const totalPurchases = document.getElementById("totalPurchases").value.trim();

    // Validation checks
    let isValid = true;
    let errorMessage = "";

    // Date validation
    if (!date) {
        isValid = false;
        errorMessage += "Please enter the date.\n";
    }

    // Customer Name validation
    if (!customerName) {
        isValid = false;
        errorMessage += "Please enter the customer name.\n";
    }

    // Phone Number validation (must be a valid phone number format, e.g., 10 digits)
    if (!/^\d{10}$/.test(phoneNumber)) {
        isValid = false;
        errorMessage += "Please enter a valid 10-digit phone number.\n";
    }

    // NIC validation (it should be a valid NIC number, alphanumeric)
    if (!nicNumber || !/^[A-Za-z0-9]+$/.test(nicNumber)) {
        isValid = false;
        errorMessage += "Please enter a valid NIC number.\n";
    }

    // Email validation (basic email format check)
    if (!/\S+@\S+\.\S+/.test(email)) {
        isValid = false;
        errorMessage += "Please enter a valid email address.\n";
    }

    // Total Purchases validation (should be a positive number)
    if (!totalPurchases || isNaN(totalPurchases) || parseFloat(totalPurchases) <= 0) {
        isValid = false;
        errorMessage += "Please enter a valid positive number for total purchases.\n";
    }

    // If all validations pass, submit the form and redirect
    if (isValid) {
        window.location.href = "../../Pages/Admin/customers.php"; // Navigate to the customers page
    } else {
        alert(errorMessage); // Show validation error messages
    }
});

/*edit staff */
document.getElementById("editStaffForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the form from submitting immediately

    // Retrieve values of the fields
    const date = document.getElementById("date").value;
    const staffId = document.getElementById("customer").value.trim();
    const phoneNumber = document.getElementById("staff").value.trim();
    const type = document.getElementById("type").value;

    // Initialize a flag to check if the form is valid
    let isValid = true;
    let errorMessage = "";

    // Date validation
    if (!date) {
        isValid = false;
        errorMessage += "Please enter the date.\n";
    }

    // Staff ID validation
    if (!staffId || isNaN(staffId) || staffId.length < 4) {
        isValid = false;
        errorMessage += "Please enter a valid Staff ID (at least 4 digits).\n";
    }

    // Phone number validation
    if (!/^\d{10}$/.test(phoneNumber)) {
        isValid = false;
        errorMessage += "Please enter a valid 10-digit phone number.\n";
    }

    // Type selection validation
    if (!type) {
        isValid = false;
        errorMessage += "Please select a staff type.\n";
    }

    // If the form is valid, submit it; otherwise, show the error message
    if (isValid) {
        // Redirect to staff.html only if validation is successful
        window.location.href = "../../Pages/Admin/Staff.html";
    } else {
        alert(errorMessage); // Display the error messages
    }
});


// Form validation logic for Edit Request Gems Details
document.getElementById("editSalesForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the form from submitting immediately

    // Retrieve values of the fields
    const gemType = document.getElementById("Gem").value.trim();
    const weight = document.getElementById("weight").value.trim();
    const shape = document.getElementById("shape").value.trim();
    const color = document.getElementById("color").value.trim();
    const req = document.getElementById("req").value.trim();

    // Initialize a flag to check if the form is valid
    let isValid = true;
    let errorMessage = "";

    // Gemstone Type validation
    if (!gemType) {
        isValid = false;
        errorMessage += "Please enter a gemstone type.\n";
    }

    // Carat Weight validation
    if (!weight || weight <= 0) {
        isValid = false;
        errorMessage += "Please enter a valid carat weight.\n";
    }

    // Shape validation
    if (!shape) {
        isValid = false;
        errorMessage += "Please enter a shape.\n";
    }

    // Color validation
    if (!color) {
        isValid = false;
        errorMessage += "Please enter a color.\n";
    }

    // Special Requirement validation
    if (!req) {
        isValid = false;
        errorMessage += "Please enter any special requirements.\n";
    }

    // If the form is valid, submit it; otherwise, show the error message
    if (isValid) {
        alert("Form filled correctly! Redirecting to the request page.");
        // Redirect to request.html only if validation is successful
        window.location.href = "../Pages/Admin/request.html";
    } else {
        alert(errorMessage); // Display the error messages
    }
});
