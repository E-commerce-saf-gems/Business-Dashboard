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
                        <a href="../../Pages/Customer/customers.php">
                            <i class='bx bxs-inbox'></i>
                            <span class="text">Customers</span>
                        </a>
                    </li>

                    <li>
                        <a href="../../Pages/Staff/Staff.php">
                            <i class='bx bx-clipboard' ></i>
                            <span class="text">Staff</span>
                        </a>
                    </li>

                </ul>
            </section>

            <section id="content">
                <nav>
                   
                   <div class="profile">
                        <i class='bx bx-user' id="profile-icon"></i>
                        <ul class="dropdown-menu">
                            <li><a href="../../../Admin_Dashboard/Pages/Profile/profile.html" class="dropdown-item">Profile</a></li>
                            <li><a href="../../../Login/logout.php" class="dropdown-item" id="logout">Logout</a></li>
                        </ul>
                    </div>
                </nav>
            </section>
        `;
    }
}


document.addEventListener("DOMContentLoaded", function () {
    const profileIcon = document.getElementById("profile-icon");
    const profileMenu = document.querySelector(".profile");

    profileIcon.addEventListener("click", function () {
        profileMenu.classList.toggle("active"); 
    });

    document.addEventListener("click", function (event) {
        if (!profileMenu.contains(event.target) && event.target !== profileIcon) {
            profileMenu.classList.remove("active");
        }
    });
});

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
    updateActiveMenu();

    const profileIcon = document.getElementById("profile-icon");
    const profileMenu = document.querySelector(".profile");

    profileIcon.addEventListener("click", function (e) {
        e.stopPropagation(); 
        profileMenu.classList.toggle("active");
    });

    document.addEventListener("click", function (e) {
        if (!profileMenu.contains(e.target)) {
            profileMenu.classList.remove("active");
        }
    });
});
