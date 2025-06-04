document.addEventListener("DOMContentLoaded", () => {
    fetch("php/get_freelancer_milestones.php")
      .then(res => res.json())
      .then(data => {
        const list = document.getElementById("milestone-list");
  
        if (data.length === 0) {
          list.innerHTML = "<p>No milestones found for your projects.</p>";
          return;
        }
  
        data.forEach(milestone => {
          const div = document.createElement("div");
          div.className = "milestone";
          div.innerHTML = `
            <h4>${milestone.job_title}</h4>
            <p><strong>Description:</strong> ${milestone.description}</p>
            <p><strong>Amount:</strong> $${milestone.amount}</p>
            <p><strong>Due Date:</strong> ${milestone.due_date}</p>
            <p class="status ${milestone.status}">Status: ${milestone.status.replace("_", " ")}</p>
          `;
          list.appendChild(div);
        });
      })
      .catch(err => {
        document.getElementById("milestone-list").innerHTML = "<p>Error loading data.</p>";
        console.error(err);
      });
  });
  