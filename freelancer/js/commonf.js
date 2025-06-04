document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll(".navbar-nav .nav-link, .navbar-nav .dropdown-item");
    const currentPath = window.location.pathname.split("/").pop(); 

    // ✅ Remove active class from all links first
    navLinks.forEach(link => link.classList.remove("active"));

    let found = false;

    // ✅ Highlight active link and parent dropdown (if inside one)
    navLinks.forEach(link => {
        if (link.getAttribute("href") === currentPath) {
            link.classList.add("active");
            found = true;

            // Highlight parent dropdown if inside one
            const parentDropdown = link.closest(".dropdown");
            if (parentDropdown) {
                parentDropdown.querySelector(".nav-link.dropdown-toggle").classList.add("active");
            }
        }
    });

    // ✅ Remove active from Dashboard if it's not the current page
    const dashboardLink = document.querySelector(".navbar-nav .nav-link[href='dashboard.html']");
    if (dashboardLink && (!found || currentPath !== "dashboard.html")) {
        dashboardLink.classList.remove("active");
    }

    // ✅ Store active link in localStorage
    localStorage.setItem("activeNavLink", currentPath);

    // ✅ Restore active state when page reloads
    const storedActiveLink = localStorage.getItem("activeNavLink");
    if (storedActiveLink) {
        navLinks.forEach(link => {
            if (link.getAttribute("href") === storedActiveLink) {
                link.classList.add("active");

                // Ensure parent dropdown stays highlighted
                const parentDropdown = link.closest(".dropdown");
                if (parentDropdown) {
                    parentDropdown.querySelector(".nav-link.dropdown-toggle").classList.add("active");
                }
            }
        });
    }

    // ✅ Remove active from dropdown when clicking outside
    document.addEventListener("click", function (event) {
        const isDropdownItem = event.target.closest(".dropdown-menu");
        const isDropdownToggle = event.target.closest(".dropdown-toggle");

        if (!isDropdownItem && !isDropdownToggle) {
            document.querySelectorAll(".nav-link.dropdown-toggle").forEach(dropdown => {
                dropdown.classList.remove("active");
            });
        }
    });

    // ✅ Clear localStorage when user logs out
    const logoutBtn = document.querySelector("#logoutBtn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", function() {
            localStorage.removeItem("activeNavLink");
        });
    }
    fetch("../auth.php")
  .then(res => res.json())
  .then(data => {
    if (!data.loggedIn) {
      window.location.href = "../login.html";
    } else {
      console.log("✅ Logged in as", data.user_name, `(${data.user_role})`);
    }
  });
});


