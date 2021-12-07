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


<?php
$username = $_SESSION['username'];

$dist = 15;

$sql = "SELECT latitude FROM users WHERE username = '$username'";
$result =  mysqli_query($db, $sql);

while ($row = $result->fetch_assoc()) {
    $lat =  $row['latitude'];
}


$sql = "SELECT longtitude FROM users WHERE username = '$username'";
$result =  mysqli_query($db, $sql);

while ($row = $result->fetch_assoc()) {
    $long =  $row['longtitude'];
}
#echo $long;
#echo $lat;

#This is if you want to fetch the nearest person first, and then the others...There is no limit for fetching
#$sql = "SELECT * FROM users ORDER BY ((latitude-$lat)*(latitude-$lat)) + ((longtitude - $long)*(longtitude - $long)) ASC;";


$sql = "SELECT *, (6371 * acos(
      cos ( radians($lat) )
      * cos( radians( latitude ) )
      * cos( radians( longtitude ) - radians($long) )
      + sin ( radians($lat) )
      * sin( radians( latitude ) )
    )
) AS distance
FROM users
HAVING distance < 30
ORDER BY distance
LIMIT 0 , 20;";



$result = mysqli_query($db, $sql);


while ($row = $result->fetch_assoc()) {
    #echo $row['id'];
    echo $row['username']."<br>";
}



?>