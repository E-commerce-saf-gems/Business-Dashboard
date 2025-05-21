document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".delete-btn");
    
    deleteButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const confirmed = confirm("Are you sure you want to delete this user?");
            
            if (confirmed) {
                
                const row = button.closest("tr");
                row.remove();
            }
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    function setError(inputId, errorMessage) {
        const errorElement = document.getElementById(`${inputId}-error`);
        if (errorElement) {
            errorElement.textContent = errorMessage; 
        }
    }

    function clearError(inputId) {
        const errorElement = document.getElementById(`${inputId}-error`);
        if (errorElement) {
            errorElement.textContent = ""; 
        }
    }

    document.getElementById("name").addEventListener("input", function () {
        const value = this.value.trim();
        if (!/^[a-zA-Z\s]+$/.test(value)) {
            setError("name", "Name must contain only letters.");
        } else {
            clearError("name");
        }
    });

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

    document.getElementById("contactNo").addEventListener("input", function () {
        const value = this.value.trim();
        const contactNoRegex = /^\d{10}$/;

        if (!contactNoRegex.test(value)) {
            setError("contactNo", "Contact number must be exactly 10 digits.");
        } else {
            clearError("contactNo");
        }
    });

    document.getElementById("email").addEventListener("input", function () {
        const value = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(value)) {
            setError("email", "Please enter a valid email address.");
        } else {
            clearError("email");
        }
    });

    document.getElementById("nic").addEventListener("input", function () {
        const value = this.value.trim();
        const nicRegex = /^(\d{9}[Vv]|\d{12})$/;

        if (!nicRegex.test(value)) {
            setError("nic", "Please enter a valid NIC.");
        } else {
            clearError("nic");
        }
    });

    document.getElementById("AddStaffForm").addEventListener("submit", function (e) {
        const name = document.getElementById("name").value.trim();
        const password = document.getElementById("password").value.trim();
        const email = document.getElementById("email").value.trim();
        const contactNo = document.getElementById("contactNo").value.trim();
        const nic = document.getElementById("nic").value.trim();


        let isValid = true;

        if (!/^[a-zA-Z\s]+$/.test(name)) {
            setError("name", "Name must contain only letters.");
            isValid = false;
        } else {
            clearError("name");
        }

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

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            setError("email", "Please enter a valid email address.");
            isValid = false;
        } else {
            clearError("email");
        }

        const contactNoRegex = /^\d{10}$/;
        if (!contactNoRegex.test(contactNo)) {
            setError("contactNo", "Contact number must be exactly 10 digits.");
            isValid = false;
        } else {
            clearError("contactNo");
        }

        const nicRegex = /^(\d{9}[Vv]|\d{12})$/;
        if (!nicRegex.test(nic)) {
            setError("nic", "NIC must be exactly 12 digits.");
            isValid = false;
        } else {
            clearError("nic");
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
});


