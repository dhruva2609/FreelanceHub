document.addEventListener("DOMContentLoaded", function () {
    const users = [
        { id: 1, name: "John Doe", email: "john@example.com", role: "Freelancer", status: "Active" },
        { id: 2, name: "Jane Smith", email: "jane@example.com", role: "Client", status: "Suspended" },
        { id: 3, name: "Admin", email: "admin@example.com", role: "Admin", status: "Active" }
    ];

    const userList = document.getElementById("user-list");

    // Render Users in Table
    function renderUsers() {
        userList.innerHTML = ""; // Clear previous rows

        users.forEach(user => {
            const row = `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td class="status-${user.id} ${user.status === 'Active' ? 'active' : 'suspended'}">
                        ${user.status}
                    </td>
                    <td>
                        <button class="edit" onclick="editUser(${user.id})">Edit</button>
                        <button class="toggle ${user.status === 'Active' ? 'suspend' : 'activate'}"
                            onclick="toggleStatus(${user.id})">
                            ${user.status === 'Active' ? 'Suspend' : 'Activate'}
                        </button>
                    </td>
                </tr>
            `;
            userList.innerHTML += row;
        });
    }

    // Edit User
    window.editUser = function (id) {
        window.location.href = `edit-user.html?id=${id}`;
    };

    // Toggle User Status
    window.toggleStatus = function (id) {
        const user = users.find(u => u.id === id);
        if (user) {
            // Toggle status
            user.status = user.status === "Active" ? "Suspended" : "Active";

            // Re-render table to update button and status color
            renderUsers();

            // Send update to backend (optional)
            fetch(`/api/updateUserStatus?id=${id}&status=${user.status}`, { method: "POST" })
                .then(response => response.json())
                .then(data => console.log("User status updated successfully"))
                .catch(error => console.error("Error:", error));
        }
    };

    // Initial render on page load
    renderUsers();
});
