<?php

require_once $_SESSION['rootfolder'] . '/DataProvider/DataProvider.php';

/**
 * Description of NotificationProvider
 *
 * @author geischterfaescht
 */
class NotificationProvider extends DataProvider{
    
    public function InsertNewAdress($address){
        
        $sql = "INSERT INTO onTour_Notifications (mailaddress) VALUES ('$address')";
        $db = new Database();
        $db->iquery($sql);
        return $db->GetLastID();
    }  
    
    public function InsertEmailHash($hash, $id){
        $sql = "UPDATE onTour_Notifications SET mailHash = '$hash' WHERE ID = $id";
        $db = new Database();
        $db->iquery($sql);
    }
    
    public function EmailHashExist($hash){
        $sql = "SELECT * FROM onTour_Notifications WHERE mailHash = '$hash'";
        $db = new Database();
        return count($db->query($sql)) > 0;
    }
    
    public function SetValidation($hash){
        $sql = "UPDATE onTour_Notifications SET validated = 1 WHERE mailHash = '$hash'";
        $db = new Database();
        $db->iquery($sql);
    }
    
    public function GetAllNotificationAdresses(){
        $sql = "SELECT mailaddress, mailHash FROM `onTour_Notifications` where validated = 1 AND active = 1 ";
        $db = new Database();
        return $db->query($sql);
    }
    
    public function DisableNotification($hash){
        $sql = "UPDATE onTour_Notifications SET active = 0 WHERE mailHash = '$hash'";
        $db = new Database();
        $db->iquery($sql);
    }
}
