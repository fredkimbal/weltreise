<?php

require_once $_SESSION['rootfolder'] . '/API/API.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/NotificationProvider.php';

/**
 * Description of FotosAPI
 *
 * @author Andy
 */
class StartseiteAPI extends SmartyAPI {

    public function __construct($request, $origin) {
        parent::__construct($request);
        //Security Handling        
    }

    public function Site($args) {
        
        
        header("Content-Type: text/html");
        
        $smarty = $this->createSmarty('Startseite');

        $smarty->display('startseite.tpl');
    }

    public function Notification($args) {

        $provider = new NotificationProvider();

        if ($this->method === "PUT") {
            header("Content-Type: text/html");
            $address = array_shift($args);
            $id = $provider->InsertNewAdress($address);
            $this->sendValidationMail($id, $address);
        }
        if ($this->method === "UPDATE") {
            header("Content-Type: text/html;charset=utf-8");
            $hash = array_shift($args);
            $smarty = $this->createSmarty('Startseite');
            if ($provider->EmailHashExist($hash)) {                
                $provider->SetValidation($hash);
                $smarty->display('VerificationOK.tpl');                
            }
            else{
                $smarty->display('VerificationFailed.tpl');
            }
        }
        if($this->method === "DELETE"){
            header("Content-Type: text/html;charset=utf-8");
            $hash = array_shift($args);
            $smarty = $this->createSmarty('Startseite');
            if ($provider->EmailHashExist($hash)) {                
                $provider->DisableNotification($hash);
                $smarty->display('UnsubscribeOK.tpl');                
            }
            else{
                $smarty->display('UnsubscribeFailed.tpl');
            }
        }
    }

    private function sendValidationMail($id, $mailaddress) {
        $hash = md5($id + $mailaddress + $id);
        $provider = new NotificationProvider();
        $provider->InsertEmailHash($hash, $id);
        $url = "http://www.luanaundandy.ch?verify&$hash";
        $betreff = "Bitte best&auml;tige deine E-Mail Adresse";
        $nachricht = "Hallo<br/>Es freut uns sehr, dass du dich f&uuml;r unseren Reiseblog interessierts. Bitte klicke den untenstehenden Link, um dich definitiv f&uuml;r die Benachrichtigungen anzumelden.<br/><a href='$url'>click</a>";
        mail($mailaddress, $betreff, $nachricht, "From: Luana und Andy <noreply@luanaundandy.ch>\nContent-Type: text/html\n");
    }

}
