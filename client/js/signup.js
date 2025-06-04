document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("signup-form");

    if (!form) {
        console.error("Signup form not found!");
        return;
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();
        const confirmPassword = document.getElementById("confirm-password").value.trim();

        // Regular Expressions
        const nameRegex = /^[A-Za-z\s]{3,}$/; // At least 3 letters
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d!@#$%^&*()_+]{6,}$/; // At least 6 characters, one letter, one number

        // Name Validation
        if (!nameRegex.test(name)) {
            alert("Name must be at least 3 letters long and contain only alphabets.");
            return;
        }

        // Email Validation
        if (!emailRegex.test(email)) {
            alert("Enter a valid email address.");
            return;
        }

        // Password Validation
        if (!passwordRegex.test(password)) {
            alert("Password must be at least 6 characters long and contain at least one letter and one number.");
            return;
        }

        // Confirm Password Validation
        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return;
        }

        // Successful Sign-Up (Redirect or API Call)
        alert("Sign-up successful!");
        form.submit();
    });
});
