document.addEventListener("DOMContentLoaded", function () {
    fetch("php/get_saved_jobs.php")
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById("saved-jobs-list");

            if (!data.success || data.jobs.length === 0) {
                container.innerHTML = "<p>No saved jobs found.</p>";
                return;
            }

            data.jobs.forEach(job => {
                const jobCard = document.createElement("div");
                jobCard.className = "card mb-3 p-3 shadow-sm";

                jobCard.innerHTML = `
                    <h4>${job.title}</h4>
                    <p><strong>Category:</strong> ${job.category}</p>
                    <p><strong>Budget:</strong> $${job.budget}</p>
                    <p><strong>Description:</strong> ${job.description}</p>
                    <p><strong>Client:</strong> ${job.client_name}</p>
                    <button class="btn btn-sm btn-outline-danger" onclick="unsaveJob(${job.job_id})">Remove</button>
                `;

                container.appendChild(jobCard);
            });
        })
        .catch(error => {
            console.error("Error loading saved jobs:", error);
            document.getElementById("saved-jobs-list").innerHTML = "<p>Error loading saved jobs.</p>";
        });
});
