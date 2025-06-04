document.addEventListener("DOMContentLoaded", function () {
    const disputes = [
        { id: 5001, freelancer: "John Doe", client: "Jane Smith", reason: "Unpaid work", status: "Open" },
        { id: 5002, freelancer: "Michael Lee", client: "Alice Brown", reason: "Incomplete project", status: "Resolved" }
    ];

    const disputeList = document.getElementById("dispute-list");

    // Render Disputes in Table
    function renderDisputes() {
        disputeList.innerHTML = ""; // Clear previous rows

        disputes.forEach(dispute => {
            const row = `
                <tr>
                    <td>${dispute.id}</td>
                    <td>${dispute.freelancer}</td>
                    <td>${dispute.client}</td>
                    <td>${dispute.reason}</td>
                    <td class="status-${dispute.id} ${dispute.status === 'Resolved' ? 'resolved' : 'open'}">
                        ${dispute.status}
                    </td>
                    <td>
                        ${dispute.status === 'Open' ? `<button class="resolve-btn" onclick="resolveDispute(${dispute.id})">Resolve</button>` : '✔️ Resolved'}
                    </td>
                </tr>
            `;
            disputeList.innerHTML += row;
        });
    }

    // Resolve Dispute Function
    window.resolveDispute = function (id) {
        const dispute = disputes.find(d => d.id === id);
        if (dispute && dispute.status === "Open") {
            // Update Status to Resolved
            dispute.status = "Resolved";

            // Re-render table to update status
            renderDisputes();

            // Send Update to Backend (Optional)
            fetch(`/api/resolveDispute?id=${id}`, { method: "POST" })
                .then(response => response.json())
                .then(data => console.log("Dispute resolved successfully"))
                .catch(error => console.error("Error:", error));
        }
    };

    // Initial render on page load
    renderDisputes();
});
