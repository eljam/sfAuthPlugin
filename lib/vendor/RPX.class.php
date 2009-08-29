<?php

class RPX
{
    private $api = null;
    private $format = 'json';
    private $url = 'https://rpxnow.com/api/v2/';
    
    public function __construct()
    {
        $this->api = sfConfig::get('app_sf_auth_plugin_rpx_api');
    }
    
    public function setFormat($format)
    {
        $format = strtolower($format);
        if (in_array($format, array('xml', 'json'))) {
            $this->format = $format;
            return true;
        }
        
        return false;
    }
    
    public function call($method, $arguments)
    {
        $arguments['apiKey'] = $this->api;
        
        //Curl   
        $curl = curl_init($this->url . $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $arguments);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        return $this->decode(curl_exec($curl));

    }
    
    private function decode($raw)
    {
        if ($this->format == 'xml') {
            return simplexml_load_string($raw);
        }
        
        return json_decode($raw);
    }
}

?>