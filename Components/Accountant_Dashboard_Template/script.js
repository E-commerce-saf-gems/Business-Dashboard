class Dashboard extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <section id="sidebar">
                <a href="#" class="logo">
                    <img src="../../../Images/logo.png" width="90" height="90" alt="SAF GEMS">
                </a>
                <ul class="side-menu">
                    <li>
                        <a href="../../dashboard.html">
                            <i class='bx bxs-dashboard'></i>
                            <span class="text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../Pages/Sales/sales.html">
                            <i class='bx bx-chart'></i>
                            <span class="text">Sales</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../Pages/Bids/bids.html">
                            <i class='bx bx-dollar-circle'></i>
                            <span class="text">Bids</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../Pages/transactions/transactions.php">
                            <i class='bx bx-money'></i>
                            <span class="text">Transactions</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class='bx bxs-package'></i>
                            <span class="text">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../Pages/reports/reports.html">
                            <i class='bx bxs-clipboard'></i>
                            <span class="text">Reports</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../Pages/expenses/expenses.html">
                            <i class='bx bxs-credit-card'></i>
                            <span class="text">Expense Management</span>
                        </a>
                    </li>
                    
                </ul>
            </section>

            <section id="content">
                <nav>
                    <i class='bx bx-menu'></i>
                    <a href="#" class="nav-link">Categories</a>
                    <form action="#">
                        <div class="form-input">
                            <input type="search" placeholder="Search">
                            <button type="submit" class="search-btn">
                                <i class='bx bx-search'></i>
                            </button>
                        </div>
                    </form>
                    <a href="#" class="notification">
                        <i class='bx bxs-bell'></i>
                        <span class="num">8</span>
                    </a>
                    <div class="profile">
                        <i class='bx bx-user' id="profile-icon"></i>
                        <ul class="dropdown-menu">
                            <li><a href="/pages/Profile/MyDetails.html" class="dropdown-item">Profile</a></li>
                            <li><a href="../../../login/logout.php" class="dropdown-item" id="logout">Logout</a></li>
                        </ul>
                    </div>
                </nav>
            </section>
        `;
    }
}



customElements.define('dashboard-component', Dashboard);

// Function to update active class based on the current URL
function updateActiveMenu() {
    const allSideMenu = document.querySelectorAll('#sidebar .side-menu li a');
    const currentPath = window.location.pathname;

    allSideMenu.forEach(item => {
        const link = item.getAttribute('href');
        const li = item.parentElement;

        if (currentPath === link) {
            li.classList.add('active');
        } else {
            li.classList.remove('active');
        }

        item.addEventListener('click', function () {
            allSideMenu.forEach(i => {
                i.parentElement.classList.remove('active');
            });
            li.classList.add('active');
        });
    });
}

// Run the updateActiveMenu function on page load
window.addEventListener('DOMContentLoaded', updateActiveMenu);

const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})


if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}

window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})

document.addEventListener('DOMContentLoaded', function () {
    // Activate sidebar menu based on current path
    updateActiveMenu();

    const profileIcon = document.getElementById("profile-icon");
    const profileMenu = document.querySelector(".profile");

    // Toggle dropdown visibility
    profileIcon.addEventListener("click", function (e) {
        e.stopPropagation(); // Prevent click from bubbling up
        profileMenu.classList.toggle("active");
    });

    // Close dropdown if clicking outside
    document.addEventListener("click", function (e) {
        if (!profileMenu.contains(e.target)) {
            profileMenu.classList.remove("active");
        }
    });
});