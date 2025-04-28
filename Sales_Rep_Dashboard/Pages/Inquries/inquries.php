<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../../database/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Inquiries</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="./inquries.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Contact Inquiries</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="#">Inquiry List</a>
                        </li>
                    </ul>
                </div>
            </div>


            
            <div class="sales-table-container">
            <div class="table-filters">
                <label for="email-filter">Email:</label>
                <input type="text" id="email-filter" placeholder="Search by Email">
                
                <label for="phone-filter">Phone:</label>
                <input type="text" id="phone-filter" placeholder="Search by Phone">
                
                <label for="name-filter">Name:</label>
                <input type="text" id="name-filter" placeholder="Search by Name">
                
                <button class="btn-filter">Filter</button>
            </div>
</div>

            
            <div class="sales-table-container">
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Name</th>
                            <th>Message</th>
                            <th>Reply</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT inquries_id, email, phoneNo, name, message FROM contactUs_inquries ORDER BY inquries_id DESC";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['inquries_id'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['phoneNo'] . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['message'] . "</td>";
                                echo "<td><button class='btn-reply' data-email='" . $row['email'] . "'>Reply</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No inquiries found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>    
        </main>
    </section>

    
    <div id="reply-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Reply to Inquiry</h2>
            <form id="reply-form">
                <input type="hidden" id="reply-email" name="email">
                <textarea id="reply-message" name="message" placeholder="Write your reply..." required></textarea>
                <button type="submit" class="btn-send">Send Reply</button>
            </form>
        </div>
    </div>

    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    <script src="./contact.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("reply-modal");
            const closeModal = document.querySelector(".close");
            const replyButtons = document.querySelectorAll(".btn-reply");
            const replyEmailInput = document.getElementById("reply-email");
            const replyMessageInput = document.getElementById("reply-message");
            const replyForm = document.getElementById("reply-form");

            replyButtons.forEach(button => {
                button.addEventListener("click", function () {
                    replyEmailInput.value = this.dataset.email;
                    replyMessageInput.value = "";
                    modal.style.display = "block";
                });
            });

            closeModal.addEventListener("click", function () {
                modal.style.display = "none";
            });

            replyForm.addEventListener("submit", function (e) {
                e.preventDefault();
                const email = replyEmailInput.value;
                const message = replyMessageInput.value;

                fetch("send_reply.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `email=${email}&message=${encodeURIComponent(message)}`
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); 
                    modal.style.display = "none";
                })
                .catch(error => console.error("Error:", error));
            });
        });
    </script>
    <script>
        
document.querySelector(".btn-filter").addEventListener("click", () => {
    const emailFilter = document.getElementById("email-filter").value;
    const phoneFilter = document.getElementById("phone-filter").value;
    const nameFilter = document.getElementById("name-filter").value.toLowerCase();

    const rows = document.querySelectorAll(".sales-table tbody tr");

    rows.forEach(row => {
        const email = row.children[1].textContent.trim();   
        const phone = row.children[2].textContent.trim();   
        const name = row.children[3].textContent.toLowerCase().trim(); 

        let isVisible = true;

        if (emailFilter && email !== emailFilter) {
            isVisible = false;
        }

        if (phoneFilter && phone !== phoneFilter) {
            isVisible = false;
        }

        if (nameFilter && !name.includes(nameFilter)) {
            isVisible = false;
        }

        row.style.display = isVisible ? "" : "none";
    });
});
</script>


    <style>
        
        .modal { display: none; position: fixed; z-index: 1; padding-top: 100px; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: white; margin: auto; padding: 20px; border: 1px solid #888; width: 50%; text-align: center; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .btn-send { margin-top: 10px; padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; }
        textarea { width: 100%; height: 100px; margin-top: 10px; padding: 10px; }
    </style>

</body>

</html>
