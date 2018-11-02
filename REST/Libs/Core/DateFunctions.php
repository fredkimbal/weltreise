<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DateFunctions
 *
 * @author geischterfaescht
 */
class DateFunctions {

    public static function ConvertMySQLToGerman($dateString) {
        $date = new DateTime($dateString);
        return $date->format('d.m.Y');
    }

}
