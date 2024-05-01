let latitude = document.getElementById("latitude");
let longitude = document.getElementById("longitude");

function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
      console.log("Geolocation is not supported by this browser.");
    }
}
  
function showPosition(position) {
latitude.value = position.coords.latitude ;
longitude.value = position.coords.longitude;
}