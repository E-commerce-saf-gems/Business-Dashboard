document.getElementById("AddCustomerForm").addEventListener("submit", function (event) {
    event.preventDefault(); 

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

    let isValid = true;
    let errorMessage = "";

    if (!nicNumber || !/^[A-Za-z0-9]+$/.test(nicNumber)) {
        isValid = false;
        errorMessage += "Please enter a valid NIC number.\n";
    }

    if (!firstName) {
        isValid = false;
        errorMessage += "Please enter the first name.\n";
    }
    if (!lastName) {
        isValid = false;
        errorMessage += "Please enter the last name.\n";
    }

    if (!/^\d{10}$/.test(phoneNumber)) {
        isValid = false;
        errorMessage += "Please enter a valid 10-digit phone number.\n";
    }

    if (!/\S+@\S+\.\S+/.test(email)) {
        isValid = false;
        errorMessage += "Please enter a valid email address.\n";
    }

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

    if (!dob) {
        isValid = false;
        errorMessage += "Date of Birth is required.\n";
    }

    if (!token) {
        isValid = false;
        errorMessage += "Token is required.\n";
    }

    if (isValid) {
        window.location.href = "./customers.php"; 
    } else {
        alert(errorMessage);
    }
});


document.getElementById("editCustomerForm").addEventListener("submit", function (event) {
    event.preventDefault(); 

    const date = document.getElementById("date").value;
    const customerName = document.getElementById("customer").value.trim();
    const phoneNumber = document.getElementById("phone").value.trim();
    const nicNumber = document.getElementById("nic").value.trim();
    const email = document.getElementById("email").value.trim();
    const totalPurchases = document.getElementById("totalPurchases").value.trim();

    let isValid = true;
    let errorMessage = "";

    if (!date) {
        isValid = false;
        errorMessage += "Please enter the date.\n";
    }

    if (!customerName) {
        isValid = false;
        errorMessage += "Please enter the customer name.\n";
    }

    if (!/^\d{10}$/.test(phoneNumber)) {
        isValid = false;
        errorMessage += "Please enter a valid 10-digit phone number.\n";
    }

    if (!nicNumber || !/^[A-Za-z0-9]+$/.test(nicNumber)) {
        isValid = false;
        errorMessage += "Please enter a valid NIC number.\n";
    }

    if (!/\S+@\S+\.\S+/.test(email)) {
        isValid = false;
        errorMessage += "Please enter a valid email address.\n";
    }

    if (!totalPurchases || isNaN(totalPurchases) || parseFloat(totalPurchases) <= 0) {
        isValid = false;
        errorMessage += "Please enter a valid positive number for total purchases.\n";
    }

    if (isValid) {
        window.location.href = "./customers.php"; 
    } else {
        alert(errorMessage); 
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const customerFilter = document.getElementById("customer-filter");
    const filterForm = document.getElementById("filter-form");
    let debounceTimer;

    customerFilter.addEventListener("input", function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            filterForm.submit();
        }, 500); 
    });
});