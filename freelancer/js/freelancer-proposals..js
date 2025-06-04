document.addEventListener("DOMContentLoaded", () => {
    const proposalsList = document.getElementById("proposals-list");

    // Retrieve proposals from localStorage or initialize an empty array
    const proposals = JSON.parse(localStorage.getItem("proposals")) || [];

    function displayProposals() {
        proposalsList.innerHTML = "";

        if (proposals.length === 0) {
            proposalsList.innerHTML = `
                <div class="no-proposals text-center">
                    <p>You have not submitted any proposals yet.</p>
                    <a href="browse-jobs.html" class="btn btn-primary">Browse Jobs</a>
                </div>
            `;
            return;
        }

        proposals.forEach(proposal => {
            const proposalCard = document.createElement("div");
            proposalCard.classList.add("proposal-card");

            // Handle dynamic status class
            const statusClass = proposal.status.trim().toLowerCase().replace(/\s+/g, "-");

            proposalCard.innerHTML = `
                <h3 class="job-title">${proposal.jobTitle.trim()}</h3>
                <p><strong>Budget:</strong> ${proposal.budget.trim()}</p>
                <p><strong>Submitted on:</strong> ${proposal.date}</p>
                <p class="proposal-status ${statusClass}">Status: ${proposal.status}</p>
            `;

            proposalsList.appendChild(proposalCard);
        });
    }

    displayProposals();
});
