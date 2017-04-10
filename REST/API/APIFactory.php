<?php
require_once './API/Fotos/FotosAPI.php';

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
        return null;
    }
}
