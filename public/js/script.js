

// Création de la carte
var map = L.map('mapid').setView([ {{ resultat.coord.lat }},{{ resultat.coord.lon }}], 10);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);