<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
