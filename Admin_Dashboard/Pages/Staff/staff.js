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
        window.location.href = "./Staff.html";
    } else {
        // Show error messages if validation failed
        alert(errorMessage);
    }
});

/*edit customer validation */
// Select the form element


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
        window.location.href = "./Staff.html";
    } else {
        alert(errorMessage); // Display the error messages
    }
});