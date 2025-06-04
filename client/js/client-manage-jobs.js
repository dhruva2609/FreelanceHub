document.addEventListener("DOMContentLoaded", function () {
    const jobList = document.getElementById("jobList");
    const noJobs = document.getElementById("noJobs");

    // Fetch Jobs from Backend or Local Storage
    let jobs = JSON.parse(localStorage.getItem("jobs")) || [
        { title: "Website Development", category: "Web Development", budget: "$500", status: "Open" },
        { title: "Logo Design", category: "Graphic Design", budget: "$100", status: "Open" },
        { title: "SEO Optimization", category: "Marketing", budget: "$300", status: "In Progress" }
    ];

    function displayJobs() {
        jobList.innerHTML = ""; // Clear previous data

        if (jobs.length === 0) {
            noJobs.style.display = "block";
            return;
        }

        noJobs.style.display = "none";
        const fragment = document.createDocumentFragment();

        jobs.forEach((job, index) => {
            const row = document.createElement("tr");
            row.dataset.index = index;
            row.innerHTML = `
                <td>${job.title}</td>
                <td>${job.category}</td>
                <td>${job.budget}</td>
                <td>${job.status}</td>
                <td class="action-buttons">
                    <button class="view-btn">View</button>
                    <button class="delete-btn">Delete</button>
                </td>
            `;
            fragment.appendChild(row);
        });

        jobList.appendChild(fragment);
    }

    jobList.addEventListener("click", function (event) {
        const row = event.target.closest("tr");
        if (!row) return;
        const index = row.dataset.index;

        if (event.target.classList.contains("view-btn")) {
            viewProposals(index);
        } else if (event.target.classList.contains("delete-btn")) {
            deleteJob(index, row);
        }
    });

    function viewProposals(index) {
        alert(`Viewing proposals for: ${jobs[index]?.title}`);
        window.location.href = "view-proposals.html";
    }

    function deleteJob(index, row) {
        if (confirm(`Are you sure you want to delete "${jobs[index]?.title}"?`)) {
            jobs.splice(index, 1);
            localStorage.setItem("jobs", JSON.stringify(jobs)); // Save in Local Storage
            row.remove(); 
            if (jobs.length === 0) {
                noJobs.style.display = "block";
            }
        }
    }

    displayJobs(); // Load jobs when page loads
});
