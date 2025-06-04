document.addEventListener("DOMContentLoaded", function () {
    // Simulated admin details (for testing)
    const adminData = {
        name: "Admin User",
        email: "admin@example.com",
        password: "admin123"
    };

    // Get form elements safely
    const form = document.getElementById("settings-form");
    const nameField = document.getElementById("admin-name");
    const emailField = document.getElementById("admin-email");
    const passwordField = document.getElementById("admin-password");
    const confirmPasswordField = document.getElementById("confirm-password");
    const csrfToken = document.getElementById("csrf-token"); // Placeholder for CSRF security

    if (!form || !nameField || !emailField || !passwordField || !confirmPasswordField || !csrfToken) {
        console.error("Error: One or more form elements are missing.");
        return;
    }

    // Populate the form with existing data
    nameField.value = adminData.name;
    emailField.value = adminData.email;

    // Inline error message handling
    function showError(field, message) {
        let errorSpan = field.nextElementSibling;
        if (!errorSpan || !errorSpan.classList.contains("error-message")) {
            errorSpan = document.createElement("span");
            errorSpan.className = "error-message";
            field.parentNode.appendChild(errorSpan);
        }
        errorSpan.innerText = message;
    }

    function clearErrors() {
        document.querySelectorAll(".error-message").forEach((el) => el.remove());
    }

    // Form Submission Handler
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent page reload
        clearErrors(); // Clear previous error messages

        // Get new values from the form
        const newName = nameField.value.trim();
        const newEmail = emailField.value.trim();
        const newPassword = passwordField.value.trim();
        const confirmPassword = confirmPasswordField.value.trim();
        const csrfValue = csrfToken.value;

        // Regular Expressions for Validation
        const nameRegex = /^[A-Za-z\s]{2,}$/; // At least 2 letters or spaces
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Valid email format
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/; 
        // Min 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special char

        let isValid = true;

        // Name Validation
        if (!nameRegex.test(newName)) {
            showError(nameField, "Invalid Name! Name should contain only letters and spaces (min 2 characters).");
            isValid = false;
        }

        // Email Validation
        if (!emailRegex.test(newEmail)) {
            showError(emailField, "Invalid Email! Please enter a valid email address.");
            isValid = false;
        }

        // Password Validation (only if provided)
        if (newPassword !== "" && !passwordRegex.test(newPassword)) {
            showError(passwordField, "Invalid Password! Min 8 chars, include uppercase, lowercase, number, and special character.");
            isValid = false;
        }

        // Confirm Password Validation
        if (newPassword !== "" && newPassword !== confirmPassword) {
            showError(confirmPasswordField, "Passwords do not match!");
            isValid = false;
        }

        if (!isValid) return;

        // Prepare data for backend
        const requestData = {
            name: newName,
            email: newEmail,
            password: newPassword || undefined, // Send password only if provided
            csrf_token: csrfValue
        };

        // Send data to backend via Fetch API (Replace '/update-admin' with actual endpoint)
        fetch("/update-admin", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI after success
                adminData.name = newName;
                adminData.email = newEmail;
                if (newPassword !== "") adminData.password = newPassword;
                passwordField.value = ""; // Clear password fields
                confirmPasswordField.value = "";
                
                alert("Settings updated successfully!");
            } else {
                alert("Error updating settings. Please try again.");
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
