document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll(".status-btn");

    buttons.forEach(button => {
        button.addEventListener("click", function() {
            let orderId = this.getAttribute("data-order-id");
            let newStatus = this.getAttribute("data-new-status");

            fetch("updateOrderStatus.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `order_id=${orderId}&status=${newStatus}`
            })
            .then(response => response.text())
            .then(data => {
                console.log("Server response:", data); // Debugging log

                if (data.trim() === "success") {
                    // Change button text and style
                    this.textContent = "Completed";
                    this.style.background = "#28a745"; // Green color
                    this.setAttribute("data-new-status", "completed");
                } else {
                    alert("Failed to update order status: " + data);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Network error. Please try again.");
            });
        });
    });
});
