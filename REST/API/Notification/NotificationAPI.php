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
class NotificationAPI extends SmartyAPI {

    public function __construct($request, $origin) {
        parent::__construct($request);
//Security Handling        
    }

    public function Site($args) {
        header("Content-Type: text/html");

        $smarty = $this->createSmarty('Notification');

        $smarty->display('notification.tpl');
    }

    public function Send($args) {
        header("Content-Type: text/html");

        $provider = new NotificationProvider();
        $addresses = $provider->GetAllNotificationAdresses();

        echo "Nachricht wurde versendet an:\n";

        foreach ($addresses as $address) {
            $betreff = $_POST['subject'];
            $nachricht = $_POST['message'];
            $uri = "http://www.luanaundandy.ch?unsubscribe&" . $address['mailHash'];
            $unsubscribeMessage = "<br/><br/><br/><br/>Um dich von den Benachrichtigungen abzumelden klicke <a href='$uri'>hier</a> ";
            $nachricht .= $unsubscribeMessage;
            mail($address['mailaddress'], $betreff, $nachricht, "From: Luana und Andy <noreply@luanaundandy.ch>\nContent-Type: text/html\n");
            echo $address['mailaddress'] . "\n";
        }
    }

}
