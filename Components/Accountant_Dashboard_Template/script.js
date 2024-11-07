class Dashboard extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <section id="sidebar">
                <a href="#" class="logo">
                    <img src="/images/logo.png" width="90" height="90" alt="SAF GEMS">
                </a>
                <ul class="side-menu">
                    <li>
                        <a href="/Accoutant_Dashboard/dashboard.html">
                            <i class='bx bxs-dashboard'></i>
                            <span class="text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
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
                        <a href="#">
                            <i class='bx bx-money'></i>
                            <span class="text">Transactions</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class='bx bxs-inbox'></i>
                            <span class="text">Inventory</span>
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