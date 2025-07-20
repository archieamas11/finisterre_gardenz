// script.js
document.getElementById("showModal").addEventListener("click", () => {
  fetch("fetch_grave.php")
    .then((response) => response.json())
    .then((data) => {
      const container = document.getElementById("recordContainer");
      container.innerHTML = ""; // Clear previous records
      data.forEach((record) => {
        const div = document.createElement("div");
        div.innerHTML = `
                    <p><strong>Name:</strong> ${record.record_name}</p>
                    <p><strong>Birthdate:</strong> ${record.record_birth}</p>
                    <p><strong>Death Date:</strong> ${record.record_death}</p>
                    <hr>
                `;
        container.appendChild(div);
      });

      document.getElementById("modal").style.display = "flex";
    })
    .catch((err) => console.error("Error:", err));
});

document.getElementById("closeModal").addEventListener("click", () => {
  document.getElementById("modal").style.display = "none";
});
