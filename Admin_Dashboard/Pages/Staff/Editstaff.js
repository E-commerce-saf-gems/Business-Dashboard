document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("EditStaffForm").addEventListener("submit", function (e) {
        let isValid = true; // Declare isValid variable
        
        // Get form field values
        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const contactNo = document.getElementById("contactNo").value.trim();

        // Check for empty required fields
        const requiredFields = [
            { field: name, name: "name" },
            { field: email, name: "email" },
            { field: contactNo, name: "contactNo" },
        ];

        const emptyFields = requiredFields.filter(item => item.field === "");

        if (emptyFields.length > 0) {
            e.preventDefault(); // Prevent form submission
            alert("Please fill in all required fields: " + emptyFields.map(item => item.name).join(", "));
            return; // Stop execution
        }

        // Name Validation (Only letters and spaces)
        if (!/^[a-zA-Z\s]+$/.test(name)) {
            setError("name", "Name must contain only letters.");
            isValid = false;
        } else {
            clearError("name");
        }

        // Email Validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            setError("email", "Please enter a valid email address.");
            isValid = false;
        } else {
            clearError("email");
        }

        // Contact Number Validation (Exactly 10 digits)
        // Contact Number Validation (Must start with "07" and be exactly 10 digits)
        const contactNoRegex = /^07\d{8}$/;
        if (!contactNoRegex.test(contactNo)) {
            setError("contactNo", "Contact number must start with '07' and be exactly 10 digits.");
            isValid = false;
        } else {
            clearError("contactNo");
}


        // Prevent form submission if any validation fails
        if (!isValid) {
            e.preventDefault();
        }
    });

    // Helper function to set error messages
    function setError(inputId, errorMessage) {
        let errorElement = document.getElementById(`${inputId}-error`);
        if (!errorElement) {
            errorElement = document.createElement("span");
            errorElement.id = `${inputId}-error`;
            errorElement.style.color = "red";
            errorElement.style.fontSize = "12px";
            document.getElementById(inputId).after(errorElement);
        }
        errorElement.textContent = errorMessage;
    }

    // Helper function to clear error messages
    function clearError(inputId) {
        const errorElement = document.getElementById(`${inputId}-error`);
        if (errorElement) {
            errorElement.textContent = "";
        }
    }
});
