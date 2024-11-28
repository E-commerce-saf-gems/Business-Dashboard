class Dashboard extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <section id="sidebar">
                <a href="#" class="logo">
                    <img src="../../images/logo.png" width="90" height="90" alt="SAF GEMS">
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
                    <li>
                        <a href="#">
                            <i class='bx bx-dollar-circle'></i>
                            <span class="text">Bids</span>
                        </a>
                    </li>
                   
                    <li>
                        <a href="../../Pages/Inventory/inventory.php">
                            <i class='bx bxs-inbox'></i>
                            <span class="text">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class='bx bxs-user-detail'></i>
                            <span class="text">Customers</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../Pages/Meetings/meeting.php">
                            <i class='bx bx-calendar'></i>
                            <span class="text">Meetings</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../Pages/requests/requests.php"">
                            <i class='bx bxs-dashboard'></i>
                            <span class="text">Requests</span>
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
                    <a href="#">
                        <i class='bx bx-user'></i>
                    </a>
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