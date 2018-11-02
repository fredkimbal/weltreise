<?php
/**
 * Description of SettingProvider
 *
 * @author Andy
 */
class SettingProvider extends DataProvider {
  
    /**
     * 
     * @param type $key
     * @return typeGibt den Wert einer Einstellung anhand eines Key's zurück
     */
    public function GetSetting($key){
        $sql = "SELECT Setting FROM Settings WHERE SettingKey = '$key'";        
        $db = new Database();        
        $result =$db->query($sql);        
        return $result[0][0];         
    }    
}
