document.addEventListener("DOMContentLoaded", function () {
    const settingsForm = document.getElementById("settings-form");

    settingsForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(settingsForm);
        const settings = Object.fromEntries(formData.entries());

        alert(`Settings Updated Successfully!\n
        Name: ${settings.fullname}\n
        Email: ${settings.email}\n
        Notifications: ${settings.notifications}`);
    });
});
