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