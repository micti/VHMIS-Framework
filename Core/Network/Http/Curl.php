<?php

class Vhmis_Network_Http_Curl
{

    private $_resource;

    public function __construct()
    {}

    public function setRequestInfo($uri, $method = 'GET', $ref = '', $param = null, $cookie = '')
    {
        $this->_resource = curl_init();
        
        curl_setopt($this->_resource, CURLOPT_URL, $uri);
        curl_setopt($this->_resource, CURLOPT_REFERER, $ref);
        curl_setopt($this->_resource, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; en-US; rv:1.9.2.15) Gecko/20110303 Firefox/3.6.15');
        curl_setopt($this->_resource, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->_resource, CURLOPT_MAXREDIRS, 1);
        
        if ($cookie != '')
            curl_setopt($this->_resource, CURLOPT_COOKIE, $cookie);
        
        if ($method == 'POST') {
            curl_setopt($this->_resource, CURLOPT_POST, 1);
            curl_setopt($this->_resource, CURLOPT_POSTFIELDS, $param);
        }
    }

    public function sendSimpleRequest()
    {
        $result = curl_exec($this->_resource);
        curl_close($this->_resource);
        
        return $result;
    }
}