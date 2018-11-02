<?php

require_once $_SESSION['rootfolder'] . '/DataAccess/Database2.php';

/**
 * Description of DataProvider
 *
 * @author Andy
 */
class DataProvider {

    protected function Log($message, $logLevel) {        
        $logger = new KLogger($_SESSION['logpath'], KLogger::DEBUG);
        $logger->Log($message, $logLevel);
    }

}
