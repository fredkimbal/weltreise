<?php

require_once $_SESSION['rootfolder'] . '/API/SmartyAPI.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/ReportProvider.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/GpxProvider.php';
require_once $_SESSION['rootfolder'] . '/Libs/FileUploader/FileUploader.php';
require_once $_SESSION['rootfolder'] . '/Libs/Core/DateFunctions.php';
require_once $_SESSION['rootfolder'] . '/DTO/Track.php';

/**
 * MapAPI - REST Funktionen für die Karte
 *
 * @author Andy
 */
class MapAPI extends SmartyAPI {

    public function __construct($request, $origin) {
        parent::__construct($request);
//Security Handling        
    }

    private $titles = array("Training", "Velo Route", "Europa trip", "Australien / Neuseeland");

    public function Show($args) {
        $_SESSION['loadedgpx'] = [];
        $tourpart = array_shift($args);
        $this->getLocationOverview($tourpart);
    }

    public function LastPosition($args) {
        header("Content-Type: application/json");
        $tourpart = array_shift($args);

        $gpxProvider = new GpxProvider();
        $coordinates = [];
        $point = $gpxProvider->GetLastTrackPoint($tourpart)[0];

        array_push($coordinates, $point['Lat']);
        array_push($coordinates, $point['Long']);
        return json_encode($coordinates);
    }

    public function GetGpxInRange($args) {

        $latMin = array_shift($args);
        $latMax = array_shift($args);
        $longMin = array_shift($args);
        $longMax = array_shift($args);

        $prov = new GpxProvider();
        $data = $prov->getGpxInRange($latMin, $latMax, $longMin, $longMax, 1);

        $result = [];
        if (!isset($_SESSION['loadedgpx'])) {
            $_SESSION['loadedgpx'] = [];
        }
        foreach ($data as $value) {
            if (!in_array($value['ID'], $_SESSION['loadedgpx'])) {
                array_push($result, $this->getTrackGeoJson($value['ID']));
                array_push($_SESSION['loadedgpx'], $value['ID']);
            }
        }

        return json_encode($result);
    }

    public function GetJsonInRange($args) {

        $latMin = array_shift($args);
        $latMax = array_shift($args);
        $longMin = array_shift($args);
        $longMax = array_shift($args);

        $prov = new GpxProvider();
        $data = $prov->getGpxInRange($latMin, $latMax, $longMin, $longMax, 1);

        $result = [];

        foreach ($data as $value) {
            array_push($result, array("gpx" => $value['GPX']));
        }

        return json_encode($result);
    }

    public function Gpx($args) {
        $provider = new GpxProvider();
        $function = array_shift($args);

        if ($function === "Tourpart") {
            $tourpart = array_shift($args);

            $tracks = $provider->getAllTracksByTourPart($tourpart);

            $result = '[';
            for ($i = 0; $i < count($tracks); $i++) {
                $track = $tracks[$i];
                $result = $result . $this->getStartAndEndMarker($track['ID']);
                if ($i + 1 < count($tracks)) {
                    $result = $result . ",";
                }
            }
            $result = $result . ']';
        } else if ($function === "inbound") {
            header("Content-Type: application/json");
            $tourpart = array_shift($args);
            $latMin = array_shift($args);
            $latMax = array_shift($args);
            $longMin = array_shift($args);
            $longMax = array_shift($args);

            $tracks = $provider->getGpxInRange($latMin, $latMax, $longMin, $longMax, $tourpart);

            $first = true;
            $result = "[";
            foreach ($tracks as $t) {
                if (!in_array($t['ID'], $_SESSION['loadedgpx'])) {
                    array_push($_SESSION['loadedgpx'], $t['ID']);
                    if ($t['TourPart'] === $tourpart) {
                        if ($first) {
                            $first = false;
                        } else {
                            $result .= ',';
                        }

                        $trackPoints = $provider->GetTrackPointsByTrackID($t['ID']);

                        $track = new Track();

                        $track->TrackType = $t['TrackType'];

                        $track->StartPoint[0] = $trackPoints[0]['Lat'];
                        $track->StartPoint[1] = $trackPoints[0]['Long'];

                        $track->EndPoint[0] = $trackPoints[count($trackPoints) - 1]['Lat'];
                        $track->EndPoint[1] = $trackPoints[count($trackPoints) - 1]['Long'];

                        $track->TrackPoints = $trackPoints;

                        $result .= $track->ToJsonString();
                    }
                }
            }
            $result .= "]";
        }
        echo $result;
    }

    protected function SaveGpxFile() {
        $fileUploader = new FileUploader($_SESSION['rootfolder'] . '/API/Map/tempGpx/');
        $fileUploader->Upload();
    }

    protected function Training($args) {
        if ($this->method === "GET") {

            if (count($args) == 0) {
                $this->getTrainingOverview();
            } else {
                $function = array_shift($args);
                if ($function === 'track') {
                    $trackID = array_shift($args);
                    echo $this->getTrackGeoJson($trackID);
                } else if ($function === 'elevation') {
                    $trackID = array_shift($args);
                    $this->getTrackElevation($trackID);
                }
            }
        }
    }

    protected function Velo($args) {
        $_SESSION['loadedgpx'] = [];
        if ($this->method === "GET") {

            if (count($args) == 0) {
                $this->getVeloOverview();
            } else {
                $function = array_shift($args);
                if ($function === 'track') {
                    $trackID = array_shift($args);
                    $this->getTrackGeoJson($trackID);
                } else if ($function === 'elevation') {
                    $trackID = array_shift($args);
                    $this->getTrackElevation($trackID);
                } else if ($function === 'lastposition') {
                    header("Content-Type: application/json");
                    $gpxProvider = new GpxProvider();
                    $coordinates = [];
                    $point = $gpxProvider->GetLastTrackPoint(1);
                    array_push($coordinates, $point[0]);
                    array_push($coordinates, $point[1]);
                    return json_encode($coordinates);
                } else if ($function === 'details') {
                    header("Content-Type: application/json");
                    $trackID = array_shift($args);
                    return $this->getStartAndEndMarker($trackID);
                }
            }
        }
    }

    protected function Benelux($args) {
        $_SESSION['loadedgpx'] = [];
        if ($this->method === "GET") {
            if (count($args) == 0) {
                $this->getLocationOverview(2, "Benelux");
            } else {
                $function = array_shift($args);
                if ($function === 'track') {
//                    $trackID = array_shift($args);
//                    $this->getTrackGeoJson($trackID);
//                } else if ($function === 'elevation') {
//                    $trackID = array_shift($args);
//                    $this->getTrackElevation($trackID);
                } else if ($function === 'lastposition') {
                    header("Content-Type: application/json");
                    $gpxProvider = new GpxProvider();
                    $coordinates = [];
                    $point = $gpxProvider->GetLastTrackPoint(2);
                    array_push($coordinates, $point[0]);
                    array_push($coordinates, $point[1]);
                    return json_encode($coordinates);
//                } else if ($function === 'details') {
//                    header("Content-Type: application/json");
//                    $trackID = array_shift($args);
//                    return $this->getStartAndEndMarker($trackID);
                }
            }
        }
    }

    private function getStartAndEndMarker($trackID) {
        $gpxProvider = new GpxProvider();

        $result = '{ "type": "FeatureCollection",'
                . '"features":[';
        $result = $result . '{ "type": "Feature",'
                . '"geometry":';

        $trackPoints = $gpxProvider->GetTrackPointsByTrackID($trackID);
        $result = $result . '{ "type": "LineString",';
        $result = $result . '"coordinates": [';
        $first = true;
        foreach ($trackPoints as $points) {
            $lat = $points['Lat'];
            $long = $points['Long'];

            if ($first) {
                $first = false;
            } else {
                $result = $result . ", \n";
            }


            $result = $result . "[$long, $lat]";
        }

        $result = $result . ']},"properties": { }
        },';

        $start = $gpxProvider->GetFirstTrackPointByTrack(1, $trackID);
        $result = $result . '{"type": "Feature",
    "properties": {
      
    },
    "geometry": {
      "type": "Point",
      "coordinates": [' . $start[0][1] . ',' . $start[0][0] . ']
    }
  },';

        $start = $gpxProvider->GetLastTrackPointByTrack(1, $trackID);
        $result = $result . '{"type": "Feature",
    "properties": {
      "marker-color": "#00ff00"
    },
    "geometry": {
      "type": "Point",
      "coordinates": [' . $start[0][1] . ',' . $start[0][0] . ']
    }
  }';
        $result = $result . ']}';
        return $result;
    }

    private function getTrackElevation($trackID) {
//header("Content-Type: text/html");    
        error_reporting(E_ALL);
        $gpxProvider = new GpxProvider();
        $trackPoints = $gpxProvider->GetTrackPointsByTrackID($trackID);
//echo $trackID;

        $coollastLat = 0;
        $coollastLong = 0;
        $cooldistance = 0;
        $cooldistanceSum = 0;
        $d = 0;

        $elevations = [];
        $steps = [];

        $maxEle = 0;

        $distanceSteps = 1000;
        $R = 6371000; // metres
        foreach ($trackPoints as $points) {


            if ($coollastLat != 0 && $coollastLong != 0) {

                $rad1 = deg2rad($coollastLat);
                $rad2 = deg2rad($points['Lat']);
                $rad3 = deg2rad(abs($coollastLat - $points['Lat']));
                $deltaLon = deg2rad(abs($points['Long'] - $coollastLong));

                $a = sin($deltaLon / 2) * sin($deltaLon / 2) +
                        cos($rad1) * cos($rad2) *
                        sin($rad3 / 2) * sin($rad3 / 2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

                $d = $R * $c;

                if ($maxEle < $points['Ele']) {
                    $maxEle = $points['Ele'];
                }

                if ($d + $cooldistanceSum > max($steps) + $distanceSteps) {
                    array_push($elevations, $maxEle);
                    array_push($steps, max($steps) + $distanceSteps);
                    $maxEle = 0;
                }
            } else {
                $maxEle = $points['Ele'];
                array_push($elevations, $points['Ele']);
                array_push($steps, 0);
            }

            $coollastLat = $points['Lat'];
            $coollastLong = $points['Long'];
//echo "Distanz: $d. Höhe " . $points['Ele'] . "<br/>";
            $cooldistanceSum = $cooldistanceSum + $d;
        }
        array_push($elevations, $points['Ele']);
        array_push($steps, max($steps) + $distanceSteps);

//echo $cooldistanceSum;

        $scaleSteps = [];
        for ($i = 0; $i < count($steps); $i++) {
            if ($i % 10 == 0) {
                array_push($scaleSteps, $steps[$i] / 1000);
            } else {
                array_push($scaleSteps, 0.123456789);
            }
        }



        /* CAT:Line chart */

        /* pChart library inclusions */
        include($_SESSION['rootfolder'] . '/Libs/pChart2.1.4/class/pData.class.php');
        include($_SESSION['rootfolder'] . '/Libs/pChart2.1.4/class/pDraw.class.php');
        include($_SESSION['rootfolder'] . '/Libs/pChart2.1.4/class/pImage.class.php');

        /* Create and populate the pData object */
        $MyData = new pData();
        $MyData->addPoints($elevations, "Probe 1");
        $MyData->setAxisName(0, "Höhe (m.ü.M)");
        $MyData->setAxisDisplay(0, AXIS_FORMAT_METRIC);
        $MyData->addPoints($scaleSteps, "Labels");
        $MyData->setSerieDescription("Labels", "Distanz (km)");
        $MyData->setAbscissa("Labels");
        $MyData->setAbscissaName("Distanz (km)");
        $serieSettings = array("R" => 255, "G" => 204, "B" => 0, "Alpha" => 80);
        $MyData->setPalette("Probe 1", $serieSettings);


        /* Create the pChart object */
        $myPicture = new pImage(600, 250, $MyData);

        /* Draw the background */
        $Settings = array("R" => 255, "G" => 255, "B" => 255, "Dash" => 1, "DashR" => 190, "DashG" => 203, "DashB" => 107);
        $myPicture->drawFilledRectangle(0, 0, 600, 250, $Settings);

        /* Overlay with a gradient */
        $Settings = array("StartR" => 255, "StartG" => 255, "StartB" => 255, "EndR" => 152, "EndG" => 152, "EndB" => 152, "Alpha" => 50);
        $myPicture->drawGradientArea(0, 0, 600, 250, DIRECTION_VERTICAL, $Settings);

        /* Add a border to the picture */
        $myPicture->drawRectangle(0, 0, 598, 249, array("R" => 0, "G" => 0, "B" => 0));


        /* Write the chart title */
        $myPicture->setFontProperties(array("FontName" => $_SESSION['rootfolder'] . '/Libs/pChart2.1.4/fonts/Forgotte.ttf', "FontSize" => 11));
//$myPicture->drawText(250, 55, "Höhenprofil", array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

        /* Draw the scale and the 1st chart */
        $myPicture->setGraphArea(60, 30, 570, 210);
        $myPicture->drawFilledRectangle(60, 30, 570, 210, array("R" => 255, "G" => 255, "B" => 255, "Surrounding" => -200, "Alpha" => 10));
        $myPicture->drawScale(array("DrawSubTicks" => TRUE));
        $myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
        $myPicture->setFontProperties(array("FontName" => $_SESSION['rootfolder'] . '/Libs/pChart2.1.4/fonts/pf_arma_five.ttf', "FontSize" => 6));
        $myPicture->drawFilledSplineChart(array("DisplayValues" => FALSE, "R" => 153, "G" => 0, "B" => 0));
        $myPicture->setShadow(FALSE);

        /* Render the picture (choose the best way) */
        $myPicture->autoOutput($_SESSION['rootfolder'] . '/Libs/pChart2.1.4/pictures/example.drawLineChart.png');
    }

    private function getTrackGeoJson($trackID) {
        header("Content-Type: application/json");
        $gpxProvider = new GpxProvider();
        $trackPoints = $gpxProvider->GetTrackPointsByTrackID($trackID);
        $result = $result = '{ "type": "LineString",';
        $result = $result . '"coordinates": [';
        $first = true;
        foreach ($trackPoints as $points) {
            $lat = $points['Lat'];
            $long = $points['Long'];

            if ($first) {
                $first = false;
            } else {
                $result = $result . ", \n";
            }


            $result = $result . "[$long, $lat]";
        }

        $result = $result . ']}';
        return $result;
    }

    private function getTrainingOverview() {
        header("Content-Type: text/html");
        $smarty = $this->createSmarty('Map');

        $gpxProvider = new GpxProvider();
        $tracks = $gpxProvider->getAllTracksByTourPart(0);


        $trackArray = [];
        foreach ($tracks as $track) {
            $data["ID"] = $track['ID'];
            $data["TrackDate"] = DateFunctions::ConvertMySQLToGerman($track['TrackDate']);
            $data["Distance"] = $track['Distance'] / 1000 . " km";
            $data["Ascent"] = $track['Ascent'] / 100 . " m";

            array_push($trackArray, $data);
        }
        $smarty->assign('tracks', $trackArray);
        $smarty->display("Training.tpl");
    }

    private function getVeloOverview() {
        header("Content-Type: text/html");
        $smarty = $this->createSmarty('Map');

        $gpxProvider = new GpxProvider();
        $tracks = $gpxProvider->getAllTracksByTourPart(1);


        $trackArray = [];
        foreach ($tracks as $track) {
            $data["ID"] = $track['ID'];
            $data["TrackDate"] = DateFunctions::ConvertMySQLToGerman($track['TrackDate']);
            $data["Distance"] = $track['Distance'] / 1000 . " km";
            $data["Ascent"] = $track['Ascent'] / 100 . " m";

            array_push($trackArray, $data);
        }
        $smarty->assign('tracks', $trackArray);
        $smarty->display("Velo.tpl");
    }

    private function getLocationOverview($tourPart) {
        header("Content-Type: text/html");
        $smarty = $this->createSmarty('Map');

        $smarty->assign('title', $this->titles[$tourPart]);

        $gpxProvider = new GpxProvider();
        $tracks = $gpxProvider->getAllTracksByTourPart($tourPart);


        $trackArray = [];
        foreach ($tracks as $track) {
            $data["ID"] = $track['ID'];
            $data["TrackDate"] = DateFunctions::ConvertMySQLToGerman($track['TrackDate']);
            $data["Location"] = $track['Location'];

            $coords = $gpxProvider->GetLastTrackPointByTrack($tourPart, $track['ID']);

            $data["lat"] = $coords[0][0];
            $data["long"] = $coords[0][1];

            array_push($trackArray, $data);
        }
        $smarty->assign('tracks', $trackArray);
        $smarty->display("Locations.tpl");
    }

}
