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
        window.location.href = "../../Pages/Admin/Customer/customers.php"; // Redirect to the page
    } else {
        // If there are validation errors, display them
        alert(errorMessage);
    }
});


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
        window.location.href = "../../Pages/Admin/Customer/customers.php"; // Navigate to the customers page
    } else {
        alert(errorMessage); // Show validation error messages
    }
});