function filterJobs() {
    const searchKeyword = document.getElementById("searchJob").value.trim().toLowerCase();
    const selectedCategory = document.getElementById("jobCategory").value.toLowerCase();
    const jobs = document.querySelectorAll(".job-card");

    jobs.forEach(job => {
        const jobTitle = job.querySelector(".job-title").innerText.toLowerCase();
        const jobCategory = job.querySelector(".job-category").innerText.toLowerCase();

        const matchesKeyword = !searchKeyword || jobTitle.includes(searchKeyword);
        const matchesCategory = selectedCategory === "all categories" || jobCategory.includes(selectedCategory);

        job.style.display = (matchesKeyword && matchesCategory) ? "flex" : "none";
    });
}
