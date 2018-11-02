<?php

require_once $_SESSION['rootfolder'] . '/API/API.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/NotificationProvider.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/GpxProvider.php';
require_once $_SESSION['rootfolder'] . '/Libs/FileUploader/FileUploader.php';

/**
 * Description of FotosAPI
 *
 * @author Andy
 */
class GPXAPI extends SmartyAPI {

    public function __construct($request, $origin) {
        parent::__construct($request);
//Security Handling        
    }

    public function Site($args) {
        header("Content-Type: text/html");

        $smarty = $this->createSmarty('GPX');

        $smarty->display('gpxImport.tpl');
    }

    public function File($args) {
        if ($this->method == "POST") {
            $fileUploader = new FileUploader($_SESSION['rootfolder'] . '/API/GPX/tempGPX/');
            $fileUploader->Upload();

            $reader = XMLReader::open($fileUploader->destinationFile);
           
            $gpxProvider = new GpxProvider();
            $trackID = 0;
            $trackDate = 0;
            $maxLat = '';
            $maxLong = '';
            $minLat = '';
            $minLong = '';
                  
            
            while ($reader->read()) {               
                if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'time' && $trackDate == 0) {
                    $trackDate = $reader->readInnerXML();                    
                }
                if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'gpxtrkx:Distance') {
                    $distance = $reader->readInnerXML();                                        
                }
                if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'gpxtrkx:Ascent') {
                    $ascent = $reader->readInnerXML();                                        
                }
                
                if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'trkpt') {
                    if($trackID === 0){
                        $trackID = $gpxProvider->insertTrack($trackDate, $distance, $ascent, 3);
                    }                    
                    
                    $lat = $reader->getAttribute("lat");
                    $long = $reader->getAttribute("lon");  
                    
                    if($lat < $minLat || $minLat === ''){
                        $minLat = $lat;
                    }
                    if($lat > $maxLat){
                        $maxLat = $lat;
                    }
                    if($long < $minLong || $minLong === ''){
                        $minLong = $long;
                    }
                    if($long > $maxLong){
                        $maxLong = $long;
                    }
                    
                    while ($reader->read()) {                        
                        if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'ele') {                        
                            $ele = $reader->readInnerXML();
                        }
                        if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'time') {
                            $time = $reader->readInnerXML();
                            break;
                        }
                    }                   
                    
                    $gpxProvider->insertTrackPoint(
                            $lat, 
                            $long,
                            $ele,
                            $time,
                            $trackID);
                    $gpxProvider->updateMaxCoords($minLat, $maxLat, $minLong, $maxLong, $trackID);

                }
            }
        }
    }

}
