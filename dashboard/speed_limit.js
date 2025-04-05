
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("speed-form").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission

        const speedInput = document.getElementById("speed_limit").value;
        const messageDiv = document.getElementById("message");

        fetch("update_speed_limit.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "speed_limit=" + encodeURIComponent(speedInput)
        })
        .then(response => response.json())
        .then(data => {
            messageDiv.textContent = data.message;
            messageDiv.style.color = data.status === "success" ? "green" : "red";
            messageDiv.style.display = "block";

            // Hide message after 3 seconds
            setTimeout(() => {
                messageDiv.style.display = "none";
            }, 3000);
        })
        .catch(error => {
            console.error("Error:", error);
        });
    });
});


