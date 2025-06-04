document.addEventListener("DOMContentLoaded", function () {
    const transactions = [
        { id: 1001, user: "John Doe", amount: "$200", status: "Pending", type: "Withdrawal" },
        { id: 1002, user: "Jane Smith", amount: "$500", status: "Completed", type: "Deposit" }
    ];

    const transactionList = document.getElementById("transaction-list");

    // Function to Render Transactions
    function renderTransactions() {
        transactionList.innerHTML = ""; // Clear previous rows
        
        if (transactions.length === 0) {
            transactionList.innerHTML = `<tr><td colspan="6" style="text-align: center;">No Transactions Available</td></tr>`;
            return;
        }

        transactions.forEach(transaction => {
            const row = `
                <tr>
                    <td>${transaction.id}</td>
                    <td>${transaction.user}</td>
                    <td>${transaction.amount}</td>
                    <td class="status-${transaction.id} status-${transaction.status}">${transaction.status}</td>
                    <td>${transaction.type}</td>
                    <td>
                        <button class="approve" onclick="approveTransaction(${transaction.id})">Approve</button>
                        <button class="reject" onclick="rejectTransaction(${transaction.id})">Reject</button>
                    </td>
                </tr>
            `;
            transactionList.innerHTML += row;
        });
    }

    // Approve Transaction
    window.approveTransaction = function (id) {
        const transaction = transactions.find(t => t.id === id);
        if (transaction) {
            transaction.status = "Approved";
            renderTransactions(); // Re-render table to update status
        }
    };

    // Reject Transaction
    window.rejectTransaction = function (id) {
        const transaction = transactions.find(t => t.id === id);
        if (transaction) {
            transaction.status = "Rejected";
            renderTransactions(); // Re-render table to update status
        }
    };

    // Initial Render
    renderTransactions();
});
