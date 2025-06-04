document.addEventListener("DOMContentLoaded", function () {
    const jobs = [
        { id: 101, title: "Website Design", client: "Jane Smith", status: "Pending" },
        { id: 102, title: "Mobile App Development", client: "David Lee", status: "Approved" }
    ];

    // Function to Render Jobs in Table
    function renderJobs() {
        const jobList = document.getElementById("job-list");
        jobList.innerHTML = ""; // Clear existing rows

        jobs.forEach(job => {
            const row = `
                <tr>
                    <td>${job.id}</td>
                    <td>${job.title}</td>
                    <td>${job.client}</td>
                    <td class="status-${job.id} status-${job.status}">${job.status}</td>
                    <td>
                        <button class="approve" onclick="approveJob(${job.id})">Approve</button>
                        <button class="reject" onclick="rejectJob(${job.id})">Reject</button>
                    </td>
                </tr>
            `;
            jobList.innerHTML += row;
        });
    }

    // Approve Job Function
    window.approveJob = function (id) {
        const job = jobs.find(j => j.id === id);
        if (job) {
            job.status = "Approved";
            renderJobs(); // Re-render the table after updating status
        }
    };

    // Reject Job Function
    window.rejectJob = function (id) {
        const job = jobs.find(j => j.id === id);
        if (job) {
            job.status = "Rejected";
            renderJobs(); // Re-render the table after updating status
        }
    };

    // Initial Render
    renderJobs();
});
