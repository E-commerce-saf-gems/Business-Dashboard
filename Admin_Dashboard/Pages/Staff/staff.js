document.addEventListener("DOMContentLoaded", function () {
    // Helper function to set error messages
    function setError(inputId, errorMessage) {
        const errorElement = document.getElementById(`${inputId}-error`);
        if (errorElement) {
            errorElement.textContent = errorMessage; // Set error message
        }
    }

    // Helper function to clear error messages
    function clearError(inputId) {
        const errorElement = document.getElementById(`${inputId}-error`);
        if (errorElement) {
            errorElement.textContent = ""; // Clear error message
        }
    }

    // Validate Name (only letters allowed)
    document.getElementById("name").addEventListener("input", function () {
        const value = this.value.trim();
        if (!/^[a-zA-Z\s]+$/.test(value)) {
            setError("name", "Name must contain only letters.");
        } else {
            clearError("name");
        }
    });

    // Password Validation
    document.getElementById("password").addEventListener("input", function () {
        const value = this.value.trim();
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!passwordRegex.test(value)) {
            setError(
                "password",
                "Password must be at least 8 characters long, include at least one lowercase letter, one uppercase letter, one number, and one special character."
            );
        } else {
            clearError("password");
        }
    });

    // Contact Number Validation (Exactly 10 digits)
    document.getElementById("contactNo").addEventListener("input", function () {
        const value = this.value.trim();
        const contactNoRegex = /^\d{10}$/;

        if (!contactNoRegex.test(value)) {
            setError("contactNo", "Contact number must be exactly 10 digits.");
        } else {
            clearError("contactNo");
        }
    });

    // Email Validation
    document.getElementById("email").addEventListener("input", function () {
        const value = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(value)) {
            setError("email", "Please enter a valid email address.");
        } else {
            clearError("email");
        }
    });

    // Form Validation on Submit
    document.getElementById("AddStaffForm").addEventListener("submit", function (e) {
        const name = document.getElementById("name").value.trim();
        const password = document.getElementById("password").value.trim();
        const email = document.getElementById("email").value.trim();
        const contactNo = document.getElementById("contactNo").value.trim();

        let isValid = true;

        // Validate Name
        if (!/^[a-zA-Z\s]+$/.test(name)) {
            setError("name", "Name must contain only letters.");
            isValid = false;
        } else {
            clearError("name");
        }

        // Validate Password
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordRegex.test(password)) {
            setError(
                "password",
                "Password must be at least 8 characters long, include at least one lowercase letter, one uppercase letter, one number, and one special character."
            );
            isValid = false;
        } else {
            clearError("password");
        }

        // Validate Email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            setError("email", "Please enter a valid email address.");
            isValid = false;
        } else {
            clearError("email");
        }

        // Validate Contact Number
        const contactNoRegex = /^\d{10}$/;
        if (!contactNoRegex.test(contactNo)) {
            setError("contactNo", "Contact number must be exactly 10 digits.");
            isValid = false;
        } else {
            clearError("contactNo");
        }

        // Prevent Form Submission if Invalid
        if (!isValid) {
            e.preventDefault();
        }
    });
});


