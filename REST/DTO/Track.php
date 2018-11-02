<?php

/**
 * Description of Track
 *
 * @author andyn
 */
class Track {
    public $TrackPoints; 
    public $StartPoint;
    public $EndPoint;
    public $TrackType;
    
    public function ToJsonString(){
        
        $result = "";
        $result .= '{ "TrackType" : '.$this->TrackType.' ,';
        $result = $result.'"Start": { "Lat" : '.$this->StartPoint[0].', "Long" : '.$this->StartPoint[1].'},';
        $result = $result. '"End" : { "Lat": '.$this->EndPoint[0].', "Long" : '.$this->EndPoint[1].'},';
        $result .= '"Track" : [';
        $first = true;
        foreach($this->TrackPoints as $point){
            if($first){                
                $first = false;
            }
            else{
                $result.=',';
            }
            $result = $result.'{"Lat" : '.$point[0].', "Long" : '.$point[1].'}';            
        }
        $result .= "]}";
        return $result;
    }
}
    

