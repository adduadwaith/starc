document.addEventListener("DOMContentLoaded", function () {
    const canvas = document.getElementById('speedometerCanvas');
    const ctx = canvas.getContext('2d');
    let currentSpeedValue = 0;

    function drawSpeedometer(speed) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        ctx.beginPath();
        ctx.arc(canvas.width / 2, canvas.height, canvas.height - 20, Math.PI, 0);
        ctx.strokeStyle = '#ccc';
        ctx.lineWidth = 5;
        ctx.stroke();

        const angle = Math.PI + (speed / 150) * Math.PI;
        const needleLength = canvas.height - 40;
        const needleX = canvas.width / 2 + Math.cos(angle) * needleLength;
        const needleY = canvas.height + Math.sin(angle) * needleLength;

        ctx.beginPath();
        ctx.moveTo(canvas.width / 2, canvas.height);
        ctx.lineTo(needleX, needleY);
        ctx.strokeStyle = 'red';
        ctx.lineWidth = 3;
        ctx.stroke();

        ctx.font = '20px Arial';
        ctx.fillStyle = 'black';
        ctx.textAlign = 'center';
        ctx.fillText(speed + ' km/h', canvas.width / 2, canvas.height - 50);
    }

    setInterval(function () {
        currentSpeedValue = Math.floor(Math.random() * 120);
        const maxSpeedValue = Math.floor(Math.random() * 150);
        const averageSpeedValue = Math.floor(Math.random() * 100);

        document.getElementById('currentSpeed').textContent = currentSpeedValue;
        document.getElementById('maxSpeed').textContent = maxSpeedValue;
        document.getElementById('averageSpeed').textContent = averageSpeedValue;

        drawSpeedometer(currentSpeedValue);
    }, 2000);
});



function fetchSpeed() {
    fetch('fetch_speed.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('currentSpeed').innerText = data.speed;
        })
        .catch(error => console.error('Error fetching speed:', error));
}

setInterval(fetchSpeed, 5000); // Update every 5 seconds
fetchSpeed();
