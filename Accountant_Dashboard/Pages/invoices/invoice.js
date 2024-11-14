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

//EXPORT OPTION
// Function to export as PDF
function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Adjust position if needed
    doc.html(document.querySelector('.invoice-container'), {
        callback: function (pdf) {
            pdf.save("invoice.pdf");
        },
        x: 10,
        y: 10,
        width: 180, // width in mm
        windowWidth: 800 // larger windowWidth for better rendering
    });
}

// Function to export as Excel
function exportToExcel() {
    const table = document.querySelector('.invoice-container');
    const worksheet = XLSX.utils.table_to_sheet(table);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Invoice");

    // Export to Excel file
    XLSX.writeFile(workbook, "invoice.xlsx");
}
