var Map;
(function (Map) {
    var MapManager = (function () {
        function MapManager() {
        }
        MapManager.ShowMap = function (mapID) {
            if (mapID === "0") {
                MapManager.ShowTraining();
            }
            if (mapID === "1") {
                MapManager.ShowVelo();
            }
            if (mapID === "2") {
                MapManager.ShowBenelux();
            }
            if (mapID === "3") {
                MapManager.Show(mapID);
            }
        };
        MapManager.ShowTraining = function () {
            $.get("REST/api/v1/Map/Training", function (d) {
                $("#maincontentArea").html(d);
                var mymap = L.map('mapid').setView([47.17444, 8.108333], 13);
                L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(mymap);
                MapManager.geojsonLayer = L.geoJson().addTo(mymap);
                $('.trackLink').click(function (e) {
                    var trackID = $(e.currentTarget).attr('data-trackid');
                    $.get("REST/api/v1/Map/Training/track/" + trackID, function (data) {
                        MapManager.geojsonLayer.clearLayers();
                        MapManager.geojsonLayer.addLayer(L.geoJson(data));
                    });
                });
                $('.elevationChartButton').click(function (e) {
                    var id = $(e.currentTarget).attr('data-trackid');
                    $("#elevationImage").attr("src", "REST/api/v1/Map/Training/elevation/" + id);
                    $("#elevationForm").css("display", "block");
                });
                $('#closeButton').click(function (e) {
                    $("#elevationForm").css("display", "none");
                });
            });
        };
        MapManager.ShowVelo = function () {
            $.get("REST/api/v1/Map/Velo", function (d) {
                $("#maincontentArea").html(d);
                $.get("REST/api/v1/Map/Velo/lastposition/", function (d) {
                    MapManager.myMap = L.map('mapid').setView([d[0][0], d[0][1]], 10);
                    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(MapManager.myMap);
                    MapManager.geojsonLayer = L.geoJson().addTo(MapManager.myMap);
                    MapManager.currentTrackLayer = L.geoJson().addTo(MapManager.myMap);
                    MapManager.loadTrackInBound();
                    MapManager.myMap.on("moveend", function () {
                        MapManager.loadTrackInBound();
                    });
                });
                $('.trackLink').click(function (e) {
                    var trackID = $(e.currentTarget).attr('data-trackid');
                    $.get("REST/api/v1/Map/Velo/track/" + trackID, function (data) {
                        MapManager.geojsonLayer.clearLayers();
                        MapManager.geojsonLayer.addLayer(L.geoJson(data));
                    });
                });
                $('.elevationChartButton').click(function (e) {
                    var id = $(e.currentTarget).attr('data-trackid');
                    $("#elevationImage").attr("src", "REST/api/v1/Map/Velo/elevation/" + id);
                    $("#elevationForm").css("display", "block");
                });
                $('#closeButton').click(function (e) {
                    $("#elevationForm").css("display", "none");
                });
            });
        };
        MapManager.ShowBenelux = function () {
            $.get("REST/api/v1/Map/Benelux", function (d) {
                $("#maincontentArea").html(d);
                $.get("REST/api/v1/Map/Benelux/lastposition", function (d) {
                    MapManager.myMap = L.map('mapid').setView([d[0][0], d[0][1]], 7);
                    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(MapManager.myMap);
                    MapManager.geojsonLayer = L.geoJson().addTo(MapManager.myMap);
                    MapManager.currentTrackLayer = L.geoJson().addTo(MapManager.myMap);
                    MapManager.loadAllTracksByTourPart(2);
                });
                $('.trackLink').click(function (e) {
                    var lat = $(e.currentTarget).attr('data-lat');
                    var long = $(e.currentTarget).attr('data-long');
                    MapManager.myMap.setView([Number(lat), Number(long)], 7);
                });
            });
        };
        MapManager.Show = function (tourpart) {
            $.get("REST/api/v1/Map/Show/" + tourpart, function (d) {
                $("#maincontentArea").html(d);
                $.get("REST/api/v1/Map/LastPosition/" + tourpart, function (d) {
                    MapManager.myMap = L.map('mapid').setView([d[0], d[1]], 7);
                    MapManager.myMap.on("moveend", function () {
                        MapManager.loadTrackInBound2(tourpart);
                    });
                    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(MapManager.myMap);
                    MapManager.geojsonLayer = L.geoJson().addTo(MapManager.myMap);
                    MapManager.currentTrackLayer = L.geoJson().addTo(MapManager.myMap);
                    MapManager.loadTrackInBound2(tourpart);
                });
                $('.trackLink').click(function (e) {
                    var lat = $(e.currentTarget).attr('data-lat');
                    var long = $(e.currentTarget).attr('data-long');
                    MapManager.myMap.setView([Number(lat), Number(long)], 7);
                });
            });
        };
        MapManager.loadTrackInBound = function () {
            var bounds = MapManager.myMap.getBounds();
            $.get("REST/api/v1/Map/GetGpxInRange/" + bounds.getSouthEast().lat + "/" + bounds.getNorthWest().lat + "/" + bounds.getSouthEast().lng + "/" + bounds.getNorthWest().lng, function (data) {
                $.each(data, function (i, v) {
                    MapManager.geojsonLayer.addLayer(L.geoJson(JSON.parse(v)));
                });
            });
        };
        MapManager.loadTrackInBound2 = function (tourpart) {
            var bounds = MapManager.myMap.getBounds();
            $.get("REST/api/v1/Map/Gpx/inbound/" + tourpart + "/" + bounds.getSouthEast().lat + "/" + bounds.getNorthWest().lat + "/" + bounds.getSouthEast().lng + "/" + bounds.getNorthWest().lng, function (data) {
                $.each(data, function (i, v) {
                    var arr = [];
                    $.each(v.Track, function (j, t) {
                        arr.push(new L.LatLng(t.Lat, t.Long));
                    });
                    var line = new L.Polyline(arr, {
                        color: MapManager.colors[v.TrackType],
                        weight: 3,
                        opacity: 0.5,
                        smoothFactor: 1
                    });
                    MapManager.layers.push(line);
                    line.addTo(MapManager.myMap);
                    var marker = L.marker([v.Start.Lat, v.Start.Long]);
                    MapManager.layers.push(marker);
                    marker.addTo(MapManager.myMap);
                    marker = L.marker([v.End.Lat, v.End.Long]);
                    MapManager.layers.push(marker);
                    marker.addTo(MapManager.myMap);
                });
            });
        };
        MapManager.loadAllTracksByTourPart = function (tourpart) {
            var bounds = MapManager.myMap.getBounds();
            $.get("REST/api/v1/Map/Gpx/Tourpart/" + tourpart, function (data) {
                $.each(JSON.parse(data), function (i, v) {
                    MapManager.geojsonLayer.addLayer(L.geoJson(v));
                });
            });
        };
        return MapManager;
    }());
    MapManager.layers = [];
    MapManager.colors = ["blue", "darkslateblue", "red", "green", "goldenrod"];
    Map.MapManager = MapManager;
})(Map || (Map = {}));
