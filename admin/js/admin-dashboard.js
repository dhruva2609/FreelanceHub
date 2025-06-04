document.addEventListener("DOMContentLoaded", function () {
    // Simulated Data (Replace with Backend API Calls)
    const dashboardData = {
        totalUsers: 1250,
        activeJobs: 340,
        pendingWithdrawals: 15,
        openDisputes: 7,
        recentActivity: [
            { user: "John Doe", action: "Created a job post", date: "2025-03-02" },
            { user: "Jane Smith", action: "Submitted a dispute", date: "2025-03-01" },
            { user: "David Lee", action: "Withdrawn $500", date: "2025-02-28" },
        ],
    };

    // Function to safely update text content
    function updateElementText(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value !== undefined ? value : 'N/A';
        } else {
            console.error(`Element with ID "${id}" not found.`);
        }
    }

    // Updating Dashboard Stats with validation
    updateElementText("total-users", dashboardData.totalUsers);
    updateElementText("active-jobs", dashboardData.activeJobs);
    updateElementText("pending-withdrawals", dashboardData.pendingWithdrawals);
    updateElementText("open-disputes", dashboardData.openDisputes);

    // Handling Recent Activity Log
    const activityLog = document.getElementById("activity-log");
    if (activityLog) {
        activityLog.innerHTML = ""; // Clear previous content

        if (Array.isArray(dashboardData.recentActivity) && dashboardData.recentActivity.length > 0) {
            dashboardData.recentActivity.forEach(activity => {
                const row = document.createElement("tr");

                const userCell = document.createElement("td");
                userCell.textContent = activity.user || 'Unknown User';

                const actionCell = document.createElement("td");
                actionCell.textContent = activity.action || 'Unknown Action';

                const dateCell = document.createElement("td");
                dateCell.textContent = activity.date || 'Unknown Date';

                row.appendChild(userCell);
                row.appendChild(actionCell);
                row.appendChild(dateCell);

                activityLog.appendChild(row);
            });
        } else {
            const emptyRow = document.createElement("tr");
            const emptyCell = document.createElement("td");
            emptyCell.setAttribute("colspan", "3");
            emptyCell.textContent = "No recent activity available.";
            emptyRow.appendChild(emptyCell);
            activityLog.appendChild(emptyRow);
        }
    } else {
        console.error('Element with ID "activity-log" not found.');
    }
});
