document.addEventListener("DOMContentLoaded", function () {
    const forms = {
        profile: document.getElementById("profileForm"),
        security: document.getElementById("securityForm"),
        notification: document.getElementById("notificationForm"),
    };

    document.body.addEventListener("submit", function (event) {
        const form = event.target;

        if (form === forms.profile) {
            event.preventDefault();
            alert("Profile updated successfully!");
        } 
        else if (form === forms.security) {
            event.preventDefault();
            const newPassword = document.getElementById("newPassword").value;
            const confirmPassword = document.getElementById("confirmPassword").value;

            alert(newPassword === confirmPassword ? "Security settings updated!" : "Passwords do not match!");
        } 
        else if (form === forms.notification) {
            event.preventDefault();
            alert("Notification preferences saved!");
        }
    });
});
