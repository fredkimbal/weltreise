module Map {
    export class MapManager {

        private static layers = [];
        private static colors = ["blue", "darkslateblue", "red", "green", "goldenrod"];
        private static geojsonLayer: L.LayerGroup<L.ILayer>;
        private static currentTrackLayer: L.LayerGroup<L.ILayer>;
        private static myMap: L.Map;

        public static ShowMap(mapID) {
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
        }

        public static ShowTraining() {
            $.get("REST/api/v1/Map/Training", d => {
                $("#maincontentArea").html(d);

                var mymap = L.map('mapid').setView([47.17444, 8.108333], 13);

                L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(mymap);

                MapManager.geojsonLayer = L.geoJson().addTo(mymap);

                $('.trackLink').click(function (e) {
                    var trackID = $(e.currentTarget).attr('data-trackid');

                    $.get("REST/api/v1/Map/Training/track/" + trackID,
                        function (data) {
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
        }

        public static ShowVelo() {
            $.get("REST/api/v1/Map/Velo", d => {

                $("#maincontentArea").html(d);
                $.get("REST/api/v1/Map/Velo/lastposition/", d => {
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

                    $.get("REST/api/v1/Map/Velo/track/" + trackID,
                        function (data) {
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
        }

        public static ShowBenelux() {
            $.get("REST/api/v1/Map/Benelux", d => {

                $("#maincontentArea").html(d);
                $.get("REST/api/v1/Map/Benelux/lastposition", d => {
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
        }

        public static Show(tourpart) {
            $.get("REST/api/v1/Map/Show/" + tourpart, d => {
                $("#maincontentArea").html(d);
                $.get("REST/api/v1/Map/LastPosition/" + tourpart, d => {
                    MapManager.myMap = L.map('mapid').setView([d[0], d[1]], 7);

                   
                    MapManager.myMap.on("moveend", () => {
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
        }

        private static loadTrackInBound() {
            var bounds = MapManager.myMap.getBounds();
            $.get("REST/api/v1/Map/GetGpxInRange/" + bounds.getSouthEast().lat + "/" + bounds.getNorthWest().lat + "/" + bounds.getSouthEast().lng + "/" + bounds.getNorthWest().lng,
                function (data) {
                    $.each(data, function (i, v) {
                        MapManager.geojsonLayer.addLayer(L.geoJson(JSON.parse(v)));
                    });
                });
        }

        private static loadTrackInBound2(tourpart) {
            var bounds = MapManager.myMap.getBounds();
            $.get("REST/api/v1/Map/Gpx/inbound/" + tourpart + "/" + bounds.getSouthEast().lat + "/" + bounds.getNorthWest().lat + "/" + bounds.getSouthEast().lng + "/" + bounds.getNorthWest().lng,
                function (data) {
                    $.each(data, function (i, v) {
                        let arr = [];
                        $.each(<any>v.Track, (j, t) => {
                            arr.push(new L.LatLng(t.Lat, t.Long));                            
                        })
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
        }

        private static loadAllTracksByTourPart(tourpart) {
            var bounds = MapManager.myMap.getBounds();
            $.get("REST/api/v1/Map/Gpx/Tourpart/" + tourpart,
                function (data) {
                    $.each(JSON.parse(data), function (i, v) {
                        MapManager.geojsonLayer.addLayer(L.geoJson(v));
                    });
                });
        }


    }
}
