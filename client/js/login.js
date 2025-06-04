document.addEventListener("DOMContentLoaded", function () {
    let loginForm = document.getElementById("login-form");

    loginForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent actual form submission

        let email = document.getElementById("email").value.trim();
        let password = document.getElementById("password").value.trim();
        let rememberMe = document.getElementById("rememberMe").checked;

        // Email & Password Validation
        if (!validateEmail(email)) {
            alert("Please enter a valid email.");
            return;
        }
        if (password.length < 6) {
            alert("Password must be at least 6 characters.");
            return;
        }

        // Simulating user authentication (replace with actual backend API)
        let users = JSON.parse(localStorage.getItem("users")) || [];
        let user = users.find(u => u.email === email && u.password === password);

        if (user) {
            alert("Login Successful!");
            sessionStorage.setItem("loggedInUser", JSON.stringify(user));

            if (rememberMe) {
                localStorage.setItem("rememberedUser", JSON.stringify(user));
            } else {
                localStorage.removeItem("rememberedUser");
            }

            window.location.href = "dashboard.html"; // Redirect to dashboard
        } else {
            alert("Invalid email or password.");
        }
    });

    // Auto-fill remembered user
    let rememberedUser = JSON.parse(localStorage.getItem("rememberedUser"));
    if (rememberedUser) {
        document.getElementById("email").value = rememberedUser.email;
        document.getElementById("rememberMe").checked = true;
    }
});

// Function to validate email format
function validateEmail(email) {
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Show/hide password feature
function togglePassword() {
    let passwordField = document.getElementById("password");
    passwordField.type = (passwordField.type === "password") ? "text" : "password";
}


// Google OAuth Login (Simulated)
function loginWithGoogle() {
    alert("Google login is not yet integrated. Implement OAuth API here.");
}
