<?php

namespace App\Services\Xml;

use App\Services\Xml\XML2Array;

class SimpleXML {
    
    public static function sayHi() {
        echo    'hihihi';
    }
    
    public static function testXml($xml) {
        
#        $xml_array = simplexml_load_string($xml); 
        $xml_array = XML2Array::createArray($xml); 
        
        print_r($xml_array);
        
    }
    
    
    public static function xmlToArray($xml) {
        
        if (strlen($xml)) {
            
            $arrRet = XML2Array::createArray($xml); 
            
        } else {
            
            $arrRet = array(); 
            
        }
        
        return  $arrRet;
        
    }
    
}
