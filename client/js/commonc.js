document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll(".navbar-nav .nav-link, .navbar-nav .dropdown-item");
    const currentPath = window.location.pathname.split("/").pop(); // Extract current page

    // Remove active class from all links first
    navLinks.forEach(link => link.classList.remove("active"));

    let activeFound = false;

    // ✅ Highlight the active link
    navLinks.forEach(link => {
        const linkPath = link.getAttribute("href");
        
        // Match current page with nav link
        if (linkPath === currentPath) {
            link.classList.add("active");
            activeFound = true;

            // ✅ Highlight parent dropdown if inside a dropdown
            const parentDropdown = link.closest(".dropdown");
            if (parentDropdown) {
                parentDropdown.querySelector(".nav-link.dropdown-toggle").classList.add("active");
            }
        }
    });

    // ✅ Remove active class from Dashboard if it's not active
    const dashboardLink = document.querySelector(".navbar-nav .nav-link[href='dashboard.html']");
    if (dashboardLink && (!activeFound || currentPath !== "dashboard.html")) {
        dashboardLink.classList.remove("active");
    }

    // ✅ Remove dropdown active class when clicking outside
    document.addEventListener("click", function (event) {
        const isDropdownItem = event.target.closest(".dropdown-menu");
        const isDropdownToggle = event.target.closest(".dropdown-toggle");

        if (!isDropdownItem && !isDropdownToggle) {
            document.querySelectorAll(".nav-link.dropdown-toggle").forEach(dropdown => {
                dropdown.classList.remove("active");
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
});
