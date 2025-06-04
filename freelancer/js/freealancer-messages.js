document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.querySelector(".chat-messages");
    const sendButton = document.getElementById("send-button");
    const messageInput = document.getElementById("message-input");

    // ✅ Replace with actual session or login-based user data
    const senderId = sessionStorage.getItem("user_id") || 1;  // Example: Logged-in user
    const receiverId = sessionStorage.getItem("chat_partner_id") || 4;  // Example: Chat partner
    const jobId = sessionStorage.getItem("job_id") || 1;  // Example: Related job ID (if any)

    console.log("🔍 Fetching messages for:", { senderId, receiverId, jobId });  // Debugging

    if (!senderId || !receiverId) {
        console.error("🚨 Error: Missing sender_id or receiver_id.");
        return;
    }

    function fetchMessages() {
        const url = new URL("php/fetch_messages.php");
        url.searchParams.append("sender_id", senderId);
        url.searchParams.append("receiver_id", receiverId);
        url.searchParams.append("job_id", jobId);

        fetch(url)
            .then(response => response.json())
            .then(messages => {
                chatBox.innerHTML = "";
                
                messages.forEach(msg => {
                    const messageDiv = document.createElement("div");
                    messageDiv.classList.add("message", msg.sender_id == senderId ? "sent" : "received");
                    
                    let content = `<p>${msg.message_text}</p><span>${msg.created_at} - ${msg.status}</span>`;
                    
                    if (msg.attachment) {
                        content += `<br><a href="${msg.attachment}" target="_blank">📎 Attachment</a>`;
                    }

                    messageDiv.innerHTML = content;
                    chatBox.appendChild(messageDiv);
                });

                chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll
                updateMessageStatus();
            })
            .catch(error => console.error("❌ Error fetching messages:", error));
    }

    function sendMessage() {
        const messageText = messageInput.value.trim();
        if (!messageText) return;

        const formData = new FormData();
        formData.append("sender_id", senderId);
        formData.append("receiver_id", receiverId);
        formData.append("job_id", jobId);
        formData.append("message_text", messageText);

        fetch("php/send_message.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                fetchMessages();
                messageInput.value = "";
            }
        })
        .catch(error => console.error("❌ Error sending message:", error));
    }

    function updateMessageStatus() {
        fetch("php/update_message_status.php", {
            method: "POST",
            body: new URLSearchParams({ receiver_id: senderId, sender_id: receiverId })
        })
        .catch(error => console.error("❌ Error updating message status:", error));
    }

    sendButton.addEventListener("click", sendMessage);
    messageInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") sendMessage();
    });

    setInterval(fetchMessages, 3000); // Auto-refresh messages every 3 sec
    fetchMessages();  // Load messages on page load
});
