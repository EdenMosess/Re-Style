var url = "https://nominatim.openstreetmap.org/search?format=json&q=" + address;

fetch(url)
  .then(response => response.json())
  .then(data => {
    var lat = data[0].lat;
    var lon = data[0].lon;
    var mymap = L.map('map').setView([lat, lon], 14);
    //L.marker([lat, lon]).addTo(mymap).bindPopup(address).openPopup();
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
    }).addTo(mymap);
    L.circle([lat, lon], {
      color: '#6699CC',
      fillColor: '#6699CC',
      fillOpacity: 0.2,
      radius: 500
    }).addTo(mymap);
    mymap.attributionControl.setPrefix(false);
    });