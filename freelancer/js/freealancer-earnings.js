document.addEventListener("DOMContentLoaded", function () {
    const totalEarnings = document.getElementById("total-earnings");
    const pendingPayments = document.getElementById("pending-payments");
    const availableBalance = document.getElementById("available-balance");
    const transactionTable = document.querySelector("#transaction-table tbody");

    // ✅ Sample Transaction Data
    let transactions = [
        { date: "2025-02-20", client: "Client A", amount: 250.00, status: "Completed" },
        { date: "2025-02-25", client: "Client B", amount: 150.00, status: "Pending" }
    ];

    // ✅ Fetch transactions from localStorage (if available)
    const storedTransactions = JSON.parse(localStorage.getItem("transactions"));
    if (storedTransactions) {
        transactions = storedTransactions;
    }

    // ✅ Function to Update Earnings
    function updateEarnings() {
        let total = 0, pending = 0, available = 0;

        // ✅ Generate table rows dynamically
        const tableRows = transactions.map(({ date, client, amount, status }) => {
            total += amount;
            status === "Pending" ? (pending += amount) : (available += amount);
            return `<tr>
                <td>${date}</td>
                <td>${client}</td>
                <td>$${amount.toFixed(2)}</td>
                <td class="${status === "Completed" ? "text-success" : "text-warning"}">${status}</td>
            </tr>`;
        }).join("");

        // ✅ Update DOM Elements
        transactionTable.innerHTML = tableRows;
        totalEarnings.textContent = `$${total.toFixed(2)}`;
        pendingPayments.textContent = `$${pending.toFixed(2)}`;
        availableBalance.textContent = `$${available.toFixed(2)}`;

        // ✅ Store updated transactions in localStorage
        localStorage.setItem("transactions", JSON.stringify(transactions));
    }

    // ✅ Add New Transaction Function
    function addTransaction(date, client, amount, status) {
        transactions.push({ date, client, amount, status });
        updateEarnings(); // Recalculate earnings
    }

    // ✅ Clear Transactions on Logout
    const logoutBtn = document.querySelector("#logoutBtn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", function() {
            localStorage.removeItem("transactions");
            transactions = []; // Reset array
            updateEarnings(); // Update UI
        });
    }

    // ✅ Initial Load
    updateEarnings();
});
