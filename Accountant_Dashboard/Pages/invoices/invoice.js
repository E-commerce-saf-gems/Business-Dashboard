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

document.getElementById("download-pdf").addEventListener("click", function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const invoice = document.querySelector(".invoice-content");

    // Generate PDF
    doc.html(invoice, {
        callback: function (doc) {
            const pdfBlob = doc.output("blob");

            // Auto-download the PDF
            doc.save("invoice.pdf");

            // Attach to email form
            const formData = new FormData();
            formData.append("invoice", pdfBlob, "invoice.pdf");

            // Prepare the email form
            document.getElementById("email-form").onsubmit = function (event) {
                event.preventDefault(); // Prevent default form submission
                fetch("send_invoice.php", {
                    method: "POST",
                    body: formData
                })
                    .then((response) => response.text())
                    .then((data) => alert(data))
                    .catch((error) => console.error("Error:", error));
            };
        },
        x: 10,
        y: 10
    });
});

    // Export PDF and Attach to Form
    function attachPDFToForm() {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();
    
        // Render the invoice to PDF
        const invoice = document.getElementById("invoice-content");
        pdf.html(invoice, {
        callback: function (pdf) {
            const pdfBlob = pdf.output("blob");
    
            // Create a File object for the form input
            const fileInput = document.getElementById("invoice-upload");
            const file = new File([pdfBlob], "Invoice_0043.pdf", { type: "application/pdf" });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files; // Attach the file to the input
        },
        });
    }
    
    // Trigger PDF Attachment when Modal Opens
    emailPopupButton.addEventListener("click", attachPDFToForm);
  

    


