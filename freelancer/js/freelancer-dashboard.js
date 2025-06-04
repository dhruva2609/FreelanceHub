document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll(".navbar-nav .nav-link, .navbar-nav .dropdown-item");

    function setActiveLink(event) {
        if (this.getAttribute("href")?.startsWith("#") || this.getAttribute("href") === "#") {
            event.preventDefault();
        }

        // Remove active class from all links
        navLinks.forEach(link => link.classList.remove("active"));

        // Add active class to the clicked link
        this.classList.add("active");

        // Highlight parent dropdown if applicable
        const parentDropdown = this.closest(".dropdown");
        if (parentDropdown) {
            parentDropdown.querySelector(".nav-link.dropdown-toggle").classList.add("active");
        }

        // Store active link in localStorage
        localStorage.setItem("activeNavLink", this.getAttribute("href"));
    }

    // Attach event listener to all nav links
    navLinks.forEach(link => link.addEventListener("click", setActiveLink));

    // Retrieve the active link from localStorage
    const storedActiveLink = localStorage.getItem("activeNavLink");

    if (storedActiveLink) {
        let found = false;
        navLinks.forEach(link => {
            if (link.getAttribute("href") === storedActiveLink) {
                link.classList.add("active");
                found = true;

                // Ensure the dropdown stays active
                const parentDropdown = link.closest(".dropdown");
                if (parentDropdown) {
                    parentDropdown.querySelector(".nav-link.dropdown-toggle").classList.add("active");
                }
            }
        });

        // If no stored link matches, ensure the default dashboard is active
        if (!found) {
            document.querySelectorAll(".navbar-nav .nav-link").forEach(link => link.classList.remove("active"));
        }
    }

    // Remove dropdown active state when clicking outside
    document.addEventListener("click", function (event) {
        if (!event.target.closest(".dropdown-menu") && !event.target.closest(".dropdown-toggle")) {
            document.querySelectorAll(".nav-link.dropdown-toggle").forEach(dropdown => dropdown.classList.remove("active"));
        }
    });
});
