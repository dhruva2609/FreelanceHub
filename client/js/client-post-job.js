document.getElementById("postJobForm").addEventListener("submit", function (e) {
    e.preventDefault();
  
    const form = e.target;
    const formData = new URLSearchParams(new FormData(form)); // ⚠️ This makes it work with PHP $_POST
  
    fetch("php/post_job.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: formData.toString(),
      credentials: "include"
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          alert("Job posted successfully!");
          window.location.href = "client-manage-jobs.html";
        } else {
          alert("Error: " + data.message);
          console.log(data.debug);
        }
      })
      .catch((err) => {
        console.error("Error:", err);
        alert("Something went wrong.");
      });
  });
  