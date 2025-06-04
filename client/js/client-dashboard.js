document.addEventListener("DOMContentLoaded", function () {
    // Fetch Dashboard Stats from Backend API
    fetch("/api/dashboardStats")
        .then(response => response.json())
        .then(data => {
            // Extract data from response
            const { activeJobs, proposals, messages } = data;

            // Update the UI only if elements exist
            document.getElementById("active-jobs") && (document.getElementById("active-jobs").textContent = activeJobs);
            document.getElementById("proposals") && (document.getElementById("proposals").textContent = proposals);
            document.getElementById("messages") && (document.getElementById("messages").textContent = messages);
        })
        .catch(error => {
            console.error("Error fetching dashboard stats:", error);
        });
});
