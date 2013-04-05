<?php

namespace Fabian\Mandrill;

class Mandrill {
    /**
     * Mandrill API key
     * @var string
     */
    private $apiKey;
    
    /**
     * Mandrill API endpoint
     * @var string
     */
    private $apiEndpoint = "https://mandrillapp.com/api/1.0";
    
    /**
     * Input and output format
     * Currently supported only json;)
     * @var string
     */
    private $apiFormat = 'json';
    
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }
    
    /**
     * Get Mandrill API key
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Get Mandril API endpoint
     * @return type 
     */
    public function getApiEndpoint()
    {
        return $this->apiEndpoint;
    }
    
    /**
     * Call Mandril API by CURL POST
     * (Every call to Mandrill API should be POST, see: https://mandrillapp.com/api/docs/)
     * 
     * DO NOT CALL THIS DIRECTLY. USE CLASSES LIKE Fabian\Mandrill\Message FOR FACILITATION!;)
     * 
     * @param string $method API method
     * @param array $params API method params
     * @return string
     * @throws MandrillException 
     */
    public function call($method, array $params = array())
    {
        $params['key'] = $this->apiKey;
        $params = json_encode($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mandrill-Nette-PHP/0.1');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($ch, CURLOPT_URL, $this->apiEndpoint.$method.'.'
            .$this->apiFormat);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/'.$this->apiFormat)
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        
        if (curl_error($ch)) {
            throw new MandrillException(
                'curl error while calling '.$method.': '.  curl_error($ch)
            );
        }
        
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $result = json_decode($response, true);
        if ($result === NULL) {
            throw new MandrillException('Unable to parse JSON response');
        }
        if ($info['http_code'] != 200) {
            throw new MandrillException('Error '.$info['http_code']);
        }
        
        curl_close($ch);
        
        return $result;
    }
}