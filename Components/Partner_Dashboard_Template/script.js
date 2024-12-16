class Dashboard extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <section id="sidebar">
                <a href="#" class="logo">
                <img src="../../../images/logo.png" width="90" height="90" alt="SAF GEMS">
                </a>

                <ul class="side-menu">
                    <li>
                        <a href="../../dashboard.html">
                            <i class='bx bxs-dashboard'></i>
                            <span class="text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../Pages/Sales/sales.php">
                            <i class='bx bx-chart'></i>
                            <span class="text">Sales</span>
                        </a>
                    </li>
                    </li>
                    <li>
                        <a href="../../Pages/Purchases/purchases.php">
                            <i class='bx bx-calendar'></i>
                            <span class="text">Purchases</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../Pages/transactions/transactions.php">
                            <i class='bx bx-money'></i>
                            <span class="text">Transactions</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../Pages/Inventory/inventory.php">
                            <i class='bx bxs-inbox'></i>
                            <span class="text">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../Pages/Bids/bids.html">
                            <i class='bx bx-dollar-circle'></i>
                            <span class="text">Bids</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../Pages/reports/reports.html">
                            <i class='bx bxs-report'></i>
                            <span class="text">Reports</span>
                        </a>
                    </li>
                </ul>
            </section>

            <section id="content">
                <nav>
                    <i class='bx bx-menu'></i>
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
                        <span class="num">4</span>
                    </a>
                    <div class="notification-dropdown">
                        <ul>
                            <li>New Sales Update</li>
                            <li>Chamath Settled a payment</li>
                            <li>Your payment is due</li>
                            <li>View this months profite and loss</li>
                        </ul>
                    </div>
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
}) ;

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

document.querySelector('.notification').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent default link behavior
    const dropdown = document.querySelector('.notification-dropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
});

// Close the dropdown if clicking outside
document.addEventListener('click', function (e) {
    const notification = document.querySelector('.notification');
    const dropdown = document.querySelector('.notification-dropdown');
    if (!notification.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});