document.addEventListener("DOMContentLoaded", function () {
    const proposalList = document.getElementById("proposal-list");
    const noProposals = document.getElementById("noProposals");

    let proposals = [
        { freelancer: "John Doe", profilePic: "https://via.placeholder.com/60", bidAmount: "$500", duration: "5 days", coverLetter: "I am an experienced developer ready to work on this project." },
        { freelancer: "Jane Smith", profilePic: "https://via.placeholder.com/60", bidAmount: "$450", duration: "4 days", coverLetter: "I have expertise in this field and can deliver quality work quickly." }
    ];

    function displayProposals() {
        if (proposals.length === 0) {
            noProposals.classList.remove("hidden");
            proposalList.innerHTML = "";
            return;
        }

        noProposals.classList.add("hidden");
        const fragment = document.createDocumentFragment();

        proposals.forEach((proposal, index) => {
            const proposalCard = document.createElement("div");
            proposalCard.classList.add("proposal-card");
            proposalCard.dataset.index = index;
            proposalCard.innerHTML = `
                <img src="${proposal.profilePic}" alt="Profile Picture">
                <div class="proposal-info">
                    <h5>${proposal.freelancer}</h5>
                    <p><strong>Bid Amount:</strong> ${proposal.bidAmount}</p>
                    <p><strong>Duration:</strong> ${proposal.duration}</p>
                    <p>${proposal.coverLetter}</p>
                </div>
                <div class="proposal-actions">
                    <button class="accept-btn">Accept</button>
                    <button class="reject-btn">Reject</button>
                </div>
            `;
            fragment.appendChild(proposalCard);
        });

        proposalList.innerHTML = "";
        proposalList.appendChild(fragment);
    }

    proposalList.addEventListener("click", function (event) {
        const card = event.target.closest(".proposal-card");
        if (!card) return;
        
        const index = card.dataset.index;
        
        if (event.target.classList.contains("accept-btn")) {
            alert(`You have accepted ${proposals[index].freelancer}'s proposal.`);
        } 
        
        if (event.target.classList.contains("reject-btn") && confirm(`Are you sure you want to reject ${proposals[index].freelancer}'s proposal?`)) {
            proposals.splice(index, 1);
            displayProposals();
        }
    });

    displayProposals();
});
