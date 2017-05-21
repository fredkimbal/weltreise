<?php
require_once './API/Fotos/FotosAPI.php';
require_once './API/Reports/ReportsAPI.php';

/**
 * Factory Klasse, welche die API anhand des Request Types zurückgibt.
 *
 * @author Andy
 */
class APIFactory {
    public static function GetAPI($endpoint, $request, $origin){ 
        if($endpoint === "Fotos"){
            return new FotosAPI($request, $origin);
        }    
        if($endpoint === "Reports"){
            return new ReportsAPI($request, $origin);
        } 
        return null;
    }
}
