document.addEventListener("DOMContentLoaded", function () {
  getLocation(); // Load location when the page is opened
});



function getLocation() {
  if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition, handleError);
  } else {
      alert("Geolocation is not supported by this browser.");
  }
}

function showPosition(position) {
  document.getElementById('latitude').textContent = position.coords.latitude;
  document.getElementById('longitude').textContent = position.coords.longitude;
  initMap(position.coords.latitude, position.coords.longitude);
}

function handleError(error) {
  switch (error.code) {
      case error.PERMISSION_DENIED:
          alert("User denied the request for Geolocation.");
          break;
      case error.POSITION_UNAVAILABLE:
          alert("Location information is unavailable.");
          break;
      case error.TIMEOUT:
          alert("The request to get user location timed out.");
          break;
      case error.UNKNOWN_ERROR:
          alert("An unknown error occurred.");
          break;
  }
}

function initMap(lat, lng) {
  const mapDiv = document.getElementById('map');
  const apiKey = "AIzaSyCwXY_3j4jpolyx3V6_XwgRbEPmOfVsc20"; // Replace with your actual Google Maps API key
  mapDiv.innerHTML = `<iframe width="100%" height="100%" style="border:0" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps/embed/v1/view?key=${apiKey}&center=${lat},${lng}&zoom=15"></iframe>`;
}



function fetchLocation() {
  fetch('fetch_location.php')
      .then(response => response.json())
      .then(data => {
          if (data.latitude && data.longitude) {
              document.getElementById('latitude').innerText = data.latitude;
              document.getElementById('longitude').innerText = data.longitude;
          }
      })
      .catch(error => console.error('Error fetching location:', error));
}

setInterval(fetchLocation, 5000); // Update every 5 seconds
fetchLocation();
