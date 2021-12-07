<?php
include("zugriff.php");

session_start();

#isset = ist eine variable deklariert und nicht null
if(!isset($_SESSION['username'])) {
    header("Location: _index.php");
}

$username = $_SESSION['username'];

    // handle fetch
    if (isset($_GET["lat"])) {
        $lat = $_GET["lat"];
        $long = $_GET["long"];
 
       
        $sql = "UPDATE users SET longtitude = '$long', latitude = '$lat' WHERE username = '$username';";
       
       mysqli_query($db, $sql);
        
        die("received"); // stop here
    }
    
    // no data was sent, -> show HTML document
   
?><!doctype html>
<html>
 
<head>
    <title>Geolocation-Test</title>
    <meta charset="utf-8">
</head>
 
<body>
    <div id="geoinfo"></div>
    <script>
const geoinfo = document.getElementById("geoinfo"); // not necessary thanks to IE quirk but good practice
 
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
        const { latitude, longitude, altitude } = position.coords;
        geoinfo.innerHTML = `Breitengrad: ${latitude}, Längengrad: ${longitude}`;
        if (altitude) geoinfo.innerHTML += `, Höhe über Meeresspiegel: ${altitude}`;
        // send to server
        sendData(latitude, longitude);
    });
} else {
    geoinfo.innerHTML = 'Dieser Browser unterstützt die Abfrage der Geolocation nicht.';
}
 
async function sendData(lat, long) {
    const response = await fetch(`?lat=${lat}&long=${long}`); // send data to this script, regardless of filename
    if (response.ok) {
        const text = await response.text();
        if (text == "received") alert("Geodaten erfolgreich zum Server gesendet.");
    }
}   
    </script>
</body>
 
<html>
