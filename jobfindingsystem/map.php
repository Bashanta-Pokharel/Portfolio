<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Select Place</title>

<link rel="stylesheet"
 href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
body {
    font-family: Arial;
    background: #f0f2f5;
}
.container {
    width: 50%;
    margin: 30px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
}
input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
}
#map {
    height: 350px;
    border-radius: 10px;
}
.btn {
    padding: 10px 20px;
    background: green;
    color: white;
    border: none;
    cursor: pointer;
}
</style>
</head>

<body>

<div class="container">
    <h2>Select Job Location</h2>

    <!-- ✅ GET METHOD -->
    <form method="get" action="try.php">
        <label>Place Name</label>
        <input type="text" name="location" id="placename" readonly required>

        <div id="map"></div><br>

        <button type="submit" class="btn">Use This Location</button>
    </form>
</div>

<script>
var map = L.map('map').setView([27.7172, 85.3240], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

var marker;

map.on('click', function(e) {

    if (marker) map.removeLayer(marker);

    marker = L.marker(e.latlng).addTo(map);

    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('placename').value =
                data.display_name || "Unknown place";
        });
});
</script>

</body>
</html>
