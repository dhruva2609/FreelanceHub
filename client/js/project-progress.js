document.addEventListener("DOMContentLoaded", function () {
    fetch('php/client-project-progress.php')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          document.getElementById('milestone-container').innerHTML = `<p class="text-danger">${data.error}</p>`;
          return;
        }
  
        const container = document.getElementById('milestone-container');
        container.innerHTML = ''; // Clear existing content
  
        if (data.milestones.length === 0) {
          container.innerHTML = `<p class="text-muted">No milestones found for your projects.</p>`;
        } else {
          data.milestones.forEach(milestone => {
            const card = document.createElement('div');
            card.className = 'card mb-3 shadow-sm';
  
            card.innerHTML = `
              <div class="card-body">
                <h5 class="card-title">${milestone.title}</h5>
                <p class="card-text"><strong>Milestone:</strong> ${milestone.description}</p>
                <p class="card-text"><strong>Amount:</strong> $${milestone.amount}</p>
                <p class="card-text"><strong>Due Date:</strong> ${milestone.due_date}</p>
                <span class="badge bg-${getStatusColor(milestone.status)} text-uppercase">${milestone.status}</span>
              </div>
            `;
            container.appendChild(card);
          });
        }
  
        // Progress bar
        const progressBar = document.getElementById('progress-bar');
        progressBar.style.width = `${data.completion_percentage}%`;
        progressBar.textContent = `${data.completion_percentage}% Complete`;
      })
      .catch(error => {
        console.error('Error fetching milestone data:', error);
      });
  });
  
  function getStatusColor(status) {
    switch (status) {
      case 'completed':
        return 'success';
      case 'in_progress':
        return 'info';
      case 'pending':
        return 'warning';
      case 'cancelled':
        return 'danger';
      default:
        return 'secondary';
    }
  }
  