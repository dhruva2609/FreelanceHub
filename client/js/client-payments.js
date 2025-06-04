document.addEventListener("DOMContentLoaded", function () {
    const transactions = [
        { date: "Mar 01, 2025", freelancer: "John Doe", amount: "$500", status: "Pending" },
        { date: "Feb 25, 2025", freelancer: "Jane Smith", amount: "$800", status: "Released" },
        { date: "Feb 15, 2025", freelancer: "Mark Wilson", amount: "$1200", status: "Failed" }
    ];

    const transactionTable = document.getElementById("transactionTable");
    const fragment = document.createDocumentFragment();

    transactions.forEach(tr => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${tr.date}</td>
            <td>${tr.freelancer}</td>
            <td>${tr.amount}</td>
            <td class="status-cell">${tr.status}</td>
        `;

        const statusCell = row.querySelector(".status-cell");
        statusCell.classList.add(
            tr.status === "Released" ? "text-success" : 
            tr.status === "Pending" ? "text-warning" : 
            "text-danger"
        );

        fragment.appendChild(row);
    });

    transactionTable.appendChild(fragment);
});
